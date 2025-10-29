<?php

namespace Gone\Catalog\Block\Product\View;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Block\Product\View;
use Magento\Catalog\Helper\Product;
use Magento\Catalog\Model\ProductTypes\ConfigInterface;
use Magento\CatalogInventory\Api\StockItemCriteriaInterfaceFactory;
use Magento\CatalogInventory\Model\Stock\StockItemRepository;
use Magento\Customer\Model\Session;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Json\EncoderInterface as JsonEncoder;
use Magento\Framework\Locale\FormatInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\Stdlib\StringUtils;
use Magento\Framework\Url\EncoderInterface;

/**
 * Class SubstituteProduct - Get substitute products list for a given EOL or out of stock product
 * @package Gone\Catalog\Block\Product\View
 */
class SubstituteProduct extends View implements IdentityInterface
{
    private const CACHE_TAG = 'product_substitute';

    protected SearchCriteriaBuilder $_searchCriteriaBuilder;
    protected SortOrderBuilder $_sortOrderBuilder;
    protected StockItemRepository $_stock;
    protected StockItemCriteriaInterfaceFactory $_stockItemCriteria;
    protected array $substituteProducts;

    public function __construct(
        Context                           $context,
        EncoderInterface                  $urlEncoder,
        JsonEncoder                       $jsonEncoder,
        StringUtils                       $string,
        Product                           $productHelper,
        ConfigInterface                   $productTypeConfig,
        FormatInterface                   $localeFormat,
        Session                           $customerSession,
        ProductRepositoryInterface        $productRepository,
        PriceCurrencyInterface            $priceCurrency,
        SearchCriteriaBuilder             $searchCriteriaBuilder,
        SortOrderBuilder                  $sortOrderBuilder,
        StockItemRepository               $stockItemRepository,
        StockItemCriteriaInterfaceFactory $stockItemCriteria,
        array                             $data = []
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
        $this->productHelper = $productHelper;
        $this->_stock = $stockItemRepository;
        $this->_stockItemCriteria = $stockItemCriteria;
    }

    /**
     * Return identifiers for produced content
     *
     * @return array
     */
    public function getIdentities()
    {
        $identities = [\Magento\Catalog\Model\Product::CACHE_TAG . '_' . self::CACHE_TAG];
        if ($substituteProducts = $this->getSubstituteProductsInfos()) {
            foreach ($substituteProducts as $product) {
                if ($product instanceof IdentityInterface) {
                    $identities = array_merge($identities, $product->getIdentities());
                }
            }
        }
        return $identities;
    }

    /**
     * @return array|false
     */
    public function getSubstituteProducts()
    {
        $product = $this->getProduct();

        $criteria = $this->_stockItemCriteria->create();
        $criteria->setProductsFilter($product->getId());
        $stock = $this->_stock->getList($criteria)->getItems();

        foreach ($stock as $productStock) {
            if ($productStock->getQty() > 0) {
                return false;
            }
            break;
        }
        return $this->productHelper->getSubstitutesProductsList($this->getProduct());
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
