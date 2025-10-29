<?php

namespace Magedelight\Productpdf\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $_storeManager;
    
    protected $_customerSession;
    
    public function __construct(\Magento\Framework\App\Helper\Context $context, \Magento\Store\Model\StoreManager $storeManager, \Magento\Customer\Model\Session $customerSession)
    {
        $this->_storeManager = $storeManager;
        $this->_customerSession = $customerSession;
        parent::__construct($context);
    }
    
    public function isEnabled()
    {
        if ($this->getConfig('md_productpdf/general/enabled')) {
            return true;

        } else {
            
            return false;
        }
    }


    public function getConfig($config_path)
    {
        return $this->scopeConfig->getValue(
            $config_path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    
    public function isAllowGroups()
    {
        $isAllowed = false;
        if ($this->scopeConfig->getValue("md_productpdf/general/enabled")) {
            $customerGroupId = $this->_customerSession->getCustomerGroupId();
        
            $config = (string) $this->scopeConfig->getValue('md_productpdf/general/enable_customergroups');
            $value = [];
            if ($config != '') {
                $value = explode(',', $config);
            }

            $isAllowed = false;
            if (in_array($customerGroupId, $value)) {
                $isAllowed = true;
            }
        }
        return $isAllowed;
    }
    
    public function getHeaderLogo()
    {
        $path = null;
        $mediaPath = $this->_storeManager->getStore()->getBaseMediaDir().'/theme/productpdf/header_logo/';
        $file = $this->scopeConfig->getValue('md_productpdf/header_footer/pdf_header_image');
        if (is_string($file) && strlen($file) > 0) {
            $path = $mediaPath.''.$file;
        }
        return $path;
    }
}
