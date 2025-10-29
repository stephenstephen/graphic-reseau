<?php

namespace Gone\Catalog\Block\Product\View;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Block\Product\View;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Helper\Data as TaxHelper;

class PaypalAdv extends View
{
    private const PRIX_MIN = 40;
    private const PRIX_MAX = 2000;
    private const ATTRIBUTES_SET_FOR_PAYPAL = [
        63, //'Ecrans',
        65 //'Imprimantes'
    ];

    protected TaxHelper $taxHelper;

    public function __construct(
        \Magento\Catalog\Block\Product\Context              $context,
        \Magento\Framework\Url\EncoderInterface             $urlEncoder,
        \Magento\Framework\Json\EncoderInterface            $jsonEncoder,
        \Magento\Framework\Stdlib\StringUtils               $string,
        \Magento\Catalog\Helper\Product                     $productHelper,
        \Magento\Catalog\Model\ProductTypes\ConfigInterface $productTypeConfig,
        \Magento\Framework\Locale\FormatInterface           $localeFormat,
        \Magento\Customer\Model\Session                     $customerSession,
        ProductRepositoryInterface                          $productRepository,
        \Magento\Framework\Pricing\PriceCurrencyInterface   $priceCurrency,
        TaxHelper                                           $taxHelper,
        array                                               $data = []
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
        $this->taxHelper = $taxHelper;
    }

    private Product $_product;

    /**
     * @param $product
     * @return Product
     */
    public function setProduct($product)
    {
        $this->_product = $product;
    }

    /**
     * @return true|false
     */
    public function isPaypalAuth()
    {
	return false;

        $product = $this->_product ?? $this->getProduct();
        if (!in_array($product->getAttributeSetId(), self::ATTRIBUTES_SET_FOR_PAYPAL)) {
            return false;
        }

        $price = $this->taxHelper->getTaxPrice(
            $product, $product->getFinalPrice(), true
        );

        if ($price < self::PRIX_MIN || $price > self::PRIX_MAX) {
            return false;
        }

        return true;
    }
}
