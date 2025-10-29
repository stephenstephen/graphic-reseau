<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Gone\Catalog\Helper;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Attribute\Config;
use Magento\Catalog\Model\Product as ModelProduct;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Catalog\Model\Session;
use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Asset\Repository;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Catalog category helper
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Product extends \Magento\Catalog\Helper\Product
{
    protected CollectionFactory $_productCollectionFactory;
    protected ResponseInterface $_response;
    protected ManagerInterface $_messageManager;
    protected SearchCriteriaBuilder $_searchCriteriaBuilder;
    protected StockRegistryInterface $_stockStatus;

    public function __construct(
        Context                     $context,
        StoreManagerInterface       $storeManager,
        Session                     $catalogSession,
        Repository                  $assetRepo,
        Registry                    $coreRegistry,
        Config                      $attributeConfig,
        ProductRepositoryInterface  $productRepository,
        ResponseInterface           $response,
        CategoryRepositoryInterface $categoryRepository,
        SearchCriteriaBuilder       $searchCriteriaBuilder,
        StockRegistryInterface      $stockStatus,
        ManagerInterface            $messageManager,
        array                       $reindexPriceIndexerData,
        array                       $reindexProductCategoryIndexerData
    )
    {
        parent::__construct(
            $context,
            $storeManager,
            $catalogSession,
            $assetRepo,
            $coreRegistry,
            $attributeConfig,
            $reindexPriceIndexerData,
            $reindexProductCategoryIndexerData,
            $productRepository,
            $categoryRepository
        );
        $this->_response = $response;
        $this->_messageManager = $messageManager;
        $this->_searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->_stockStatus = $stockStatus;
    }

    /**
     * Inits product to be used for product controller actions and layouts
     * $params can have following data:
     *   'category_id' - id of category to check and append to product as current.
     *     If empty (except FALSE) - will be guessed (e.g. from last visited) to load as current.
     *
     * @param int $productId
     * @param Action $controller
     * @param DataObject $params
     *
     * @return bool|ModelProduct
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function initProduct($productId, $controller, $params = null)
    {
        // Prepare data for routine
        if (!$params) {
            $params = new DataObject();
        }

        // Init and load product
        $this->_eventManager->dispatch(
            'catalog_controller_product_init_before',
            ['controller_action' => $controller, 'params' => $params]
        );

        if (!$productId) {
            return false;
        }

        try {
            $product = $this->productRepository->getById($productId, false, $this->_storeManager->getStore()->getId());
        } catch (NoSuchEntityException $e) {
            return false;
        }

        //410OVERLOAD-BEG
        $this->redirectProductIfNeeded($product);
        //410OVERLOAD-END

        if (!$this->canShow($product)) {
            return false;
        }
        if (!in_array($this->_storeManager->getStore()->getWebsiteId(), $product->getWebsiteIds())) {
            return false;
        }

        // Load product current category
        $categoryId = $params->getCategoryId();
        if (!$categoryId && $categoryId !== false) {
            $lastId = $this->_catalogSession->getLastVisitedCategoryId();
            if ($product->canBeShowInCategory($lastId)) {
                $categoryId = $lastId;
            }
        } elseif (!$product->canBeShowInCategory($categoryId)) {
            $categoryId = null;
        }

        if ($categoryId) {
            try {
                $category = $this->categoryRepository->get($categoryId);
            } catch (NoSuchEntityException $e) {
                $category = null;
            }
            if ($category) {
                $product->setCategory($category);
                $this->_coreRegistry->register('current_category', $category);
            }
        }

        // Register current data and dispatch final events
        $this->_coreRegistry->register('current_product', $product);
        $this->_coreRegistry->register('product', $product);

        try {
            $this->_eventManager->dispatch(
                'catalog_controller_product_init_after',
                ['product' => $product, 'controller_action' => $controller]
            );
        } catch (LocalizedException $e) {
            $this->_logger->critical($e);
            return false;
        }
        return $product;
    }

    /**
     * @param ModelProduct $product
     * @return $this|false
     */
    public function redirectProductIfNeeded($product)
    {
        if ($product->isSaleable() && $product->getStatus() === Status::STATUS_ENABLED) {
            return $this;
        }

        // If product as replacement and is End Of Life
        if ($product->getEndOfLifeProduct() && !empty($product->getSubstituteProductSku())) {
            $replacementProducts = $this->getSubstitutesProductsList($product);
            $substituteProductUrl = current($replacementProducts)->getProductUrl();
            $this->_redirectPermanent($substituteProductUrl); //redir 301
            return $this;
        }
    }

    /**
     * Build List of seleable substitues products for a given product
     * @param ModelProduct $product
     * @return false|ProductInterface[]
     */
    public function getSubstitutesProductsList(ModelProduct $product)
    {

        $substitutesSkuArr = explode(';', $product->getSubstituteProductSku());
        if (!empty($substitutesSkuArr)) {
            $searchCriteria = $this->_searchCriteriaBuilder
                ->addFilter(
                    'status',
                    Status::STATUS_ENABLED,
                    'eq'
                )
                ->addFilter(
                    'sku',
                    $substitutesSkuArr,
                    'in'
                );
            $substituteProductsList = $this->productRepository->getList($searchCriteria->create());
            $substituteProductsList = $substituteProductsList->getItems();

            foreach ($substituteProductsList as $key => $substituteProduct) {
                if (!$product->isSaleable()) {
                    unset($substituteProduct[$key]);
                }
            }
            return empty($substituteProductsList) ? false : $substituteProductsList;
        }
        return false;
    }

    /**
     * @param string $url
     * @return ResponseInterface
     */
    protected function _redirectPermanent(string $url): ResponseInterface
    {
        $this->_messageManager->addNoticeMessage(__('The requested product is no more available. This is an alternative product.'));
        $this->_response->setNoCacheHeaders();
        $this->_response
            ->setRedirect($url, 301)->sendResponse();
    }

    /**
     * @param ModelProduct $product
     * @return array
     */
    public function getAvailablityStatus(ModelProduct $product): array
    {
        $stockData = $this->_stockStatus->getStockStatus($product->getId());
        $stockStatus = $stockData->getStockStatus();
        $stockQty = $stockData->getQty();

        if ($product->getEndOfLifeProduct()) {
            return [
                'className' => 'unavailable',
                'jsonLD' => 'Discontinued',
                'label' => __('Out of stock')
            ];
        } elseif ($product->isAvailable()) {
            if ($stockQty == 0 || $stockStatus == \Magento\CatalogInventory\Api\Data\StockStatusInterface::STATUS_OUT_OF_STOCK) {
                return [
                    'className' => 'supplying',
                    'jsonLD' => $product->getSupplyDelays() ? 'BackOrder' : 'OutOfStock',
                    'label' => $product->getSupplyDelays() ?
                        __('Available within %1 day(s)', $product->getSupplyDelays()) :
                        __('Unknown lead time')
                ];
            }
            return [
                'className' => 'available',
                'jsonLD' => 'InStock',
                'label' => __('In stock')
            ];
        } else {
            return [
                'className' => 'supplying',
                'jsonLD' => 'BackOrder',
                'label' => $product->getSupplyDelays() ?
                    __('Available within %1 day(s)', $product->getSupplyDelays()) :
                    __('Unknown lead time')
            ];
        }
    }

    /**
     * @param ModelProduct $product
     * @return int
     */
    public function getStock(ModelProduct $product)
    {
        $stockData = $this->_stockStatus->getStockStatus($product->getId());
        return $stockData->getQty();
    }
}
