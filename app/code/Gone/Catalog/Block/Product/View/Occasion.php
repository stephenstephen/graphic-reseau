<?php

namespace Gone\Catalog\Block\Product\View;

use Gone\Catalog\Setup\Patch\Data\AddIsRelatedToNewPoductSku;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Block\Product\View;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Catalog\Model\ProductTypes\ConfigInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Json\EncoderInterface;
use Magento\Framework\Locale\FormatInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\Stdlib\StringUtils;

class Occasion extends View implements IdentityInterface
{
    private const CACHE_TAG = 'product_occ';

    protected SearchCriteriaBuilder $_searchCriteriaBuilder;
    protected SortOrderBuilder $_sortOrderBuilder;
    protected array $usedProducts;

    public function __construct(
        Context                                 $context,
        \Magento\Framework\Url\EncoderInterface $urlEncoder,
        EncoderInterface                        $jsonEncoder,
        StringUtils                             $string,
        \Magento\Catalog\Helper\Product         $productHelper,
        ConfigInterface                         $productTypeConfig,
        FormatInterface                         $localeFormat,
        Session                                 $customerSession,
        ProductRepositoryInterface              $productRepository,
        PriceCurrencyInterface                  $priceCurrency,
        SearchCriteriaBuilder                   $searchCriteriaBuilder,
        SortOrderBuilder                        $sortOrderBuilder,
        array                                   $data = []
    )
    {
        parent::__construct(
            $context,
            $urlEncoder,
            $jsonEncoder,
            $string,
            $productHelper,
            $productTypeConfig,
            $localeFormat,
            $customerSession,
            $productRepository,
            $priceCurrency,
            $data
        );
        $this->_searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->_sortOrderBuilder = $sortOrderBuilder;
    }

    /**
     * Return identifiers for produced content
     *
     * @return array
     */
    public function getIdentities()
    {

        $usedProductsInfos = $this->getUsedProductsInfos();
        $identities = [Product::CACHE_TAG . '_' . self::CACHE_TAG];
        foreach ($usedProductsInfos['products'] as $product) {
            if ($product instanceof IdentityInterface) {
                $identities = array_merge($identities, $product->getIdentities());
            }
        }
        return $identities;
    }

    /**
     * @return array|false
     */
    public function getUsedProductsInfos()
    {
        if (!isset($this->usedProducts)) {
            $sortByPriceAsc = $this->_sortOrderBuilder
                ->setField('price')
                ->setAscendingDirection()
                ->create();

            $searchCriteria = $this->_searchCriteriaBuilder
                ->addFilter(
                    AddIsRelatedToNewPoductSku::RELATED_NEW_PRODUCT_SKU,
                    trim($this->getProduct()->getSku()),
                    'eq'
                )
                ->addFilter(
                    'status',
                    Status::STATUS_ENABLED,
                    'eq'
                )
                ->setSortOrders([$sortByPriceAsc]);

            $usedProductsInfos = $this->productRepository->getList($searchCriteria->create());
            $usedProducts = $usedProductsInfos->getItems();

            foreach ($usedProducts as $key => $product) {
                if (!$product->getIsSalable()) {
                    unset($usedProducts[$key]);
                }
            }

            $this->usedProducts = [
                'products' => $usedProducts,
                'count' => count($usedProducts)
            ];
        }
        return $this->usedProducts;
    }

    /**
     * Get Key for caching block content
     *
     * @return string
     */
    public function getCacheKey()
    {
        return parent::getCacheKey() . '_' . $this->getProduct()->getId() . '_' . self::CACHE_TAG;
    }
}
