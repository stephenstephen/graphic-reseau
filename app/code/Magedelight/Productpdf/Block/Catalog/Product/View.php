<?php

namespace Magedelight\Productpdf\Block\Catalog\Product;

use Magento\Catalog\Api\ProductRepositoryInterface;

class View extends \Magento\Catalog\Block\Product\View
{
    protected $_template = "md/productpdf/link.phtml";
    protected $_printHelper;
    protected $scopeConfig;
    public function __construct(
        \Magedelight\Productpdf\Helper\Data $printHelper,
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Url\EncoderInterface $urlEncoder,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Framework\Stdlib\StringUtils $string,
        \Magento\Catalog\Helper\Product $productHelper,
        \Magento\Catalog\Model\ProductTypes\ConfigInterface $productTypeConfig,
        \Magento\Framework\Locale\FormatInterface $localeFormat,
        \Magento\Customer\Model\Session $customerSession,
        ProductRepositoryInterface $productRepository,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        array $data = []
    ) {
        
        parent::__construct($context, $urlEncoder, $jsonEncoder, $string, $productHelper, $productTypeConfig, $localeFormat, $customerSession, $productRepository, $priceCurrency, $data);
        $this->_printHelper = $printHelper;
        $this->scopeConfig = $context->getScopeConfig();
    }
    
    public function getIsAllowed()
    {
        return $this->_printHelper->isAllowGroups();
    }
    
    public function getIsEnable()
    {
        return $this->_printHelper->isEnabled();
    }

    public function getCurrentCategoryId()
    {
        $currentCategory = $this->_coreRegistry->registry('current_category');
        if ($currentCategory) {
            return $currentCategory->getId();
        } else {
            return null;
        }
    }
}
