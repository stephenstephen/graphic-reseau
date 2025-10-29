<?php
  
namespace AtooSync\GesCom\Model\Config\Backend;

class WebsitesList
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
        $websites = $this->storeManager->getWebsites($withDefault = false);
        $item = array();
        $item['value'] = 0;
        $item['label'] = "All websites";
        $locale[] = $item;
        
        //Try to get list of locale for all stores;
        foreach($websites as $website) {
            $item = array();
            $item['value'] = $website->getWebsiteId();
            $item['label'] = $website->getName();
            $locale[] = $item;
        }
        return $locale;
    }
}