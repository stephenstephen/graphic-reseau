<?php
  
namespace AtooSync\GesCom\Model\Config\Backend;

class StoresList
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
        \Magento\Store\Model\StoreManagerInterface $storeManager
    )
    {
        $this->storeManager = $storeManager;
    }

    /**
     * Get list of Locale for all stores
     * @return array
     */
    public function toOptionArray()
    {
        //Locale code
        $locale = [];
        $stores = $this->storeManager->getStores($withDefault = false);
        $item = array();
        $item['value'] = 0;
        $item['label'] = "All stores";
        $locale[] = $item;
        
        //Try to get list of locale for all stores;
        foreach($stores as $store) {
            $item = array();
            $item['value'] = $store->getStoreId();
            $item['label'] = $store->getName();
            $locale[$store['website_id']]['label']= $this->storeManager->getWebsite($store['website_id'])->getName();
            $locale[$store['website_id']]['value'][] = $item;
        }
        return $locale;
    }
}