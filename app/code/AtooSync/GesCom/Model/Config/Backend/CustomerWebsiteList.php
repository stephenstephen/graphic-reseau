<?php
  
namespace AtooSync\GesCom\Model\Config\Backend;

class CustomerWebsiteList
{
 /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * Locale constructor.
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    )
    {
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Get list of Locale for all stores
     * @return array
     */
    public function toOptionArray()
    {
        
        $items = [];
        
        $account_share = (int)$this->scopeConfig->getValue('customer/account_share/scope', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $stores = $this->storeManager->getWebsites($withDefault = false);
        
        if ($account_share == 1) {
            foreach($stores as $store) {
                $item = array();
                $item['value'] = $store->getWebsiteId();
                $item['label'] = $store->getName();
                $items[] = $item;
            }
        } else {
            $item = array();
            $item['value'] = 0;
            $item['label'] = "Global";
            $items[] = $item;
        }

        return $items;
    }
}