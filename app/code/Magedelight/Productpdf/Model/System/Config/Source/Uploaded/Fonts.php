<?php

namespace Magedelight\Productpdf\Model\System\Config\Source\Uploaded;

class Fonts implements \Magento\Framework\Option\ArrayInterface
{
    protected $_storeManager;
    public function __construct(\Magento\Store\Model\StoreManager $storeManager)
    {
        $this->_storeManager = $storeManager;
    }
    
    public function toOptionArray()
    {
        $scanPath = $this->_storeManager->getStore()->getBaseMediaDir().DIRECTORY_SEPARATOR.'md_product_print'.DIRECTORY_SEPARATOR.'fonts'.DIRECTORY_SEPARATOR;
        $options = [];
        if (is_dir($scanPath)) {
            $scanDir = scandir($scanPath);
            $i = 0;
            foreach ($scanDir as $file) {
                $fullPath = $scanPath.''.$file;
                if (is_file($fullPath)) {
                    $options[] = ["label"=>$file, "value"=>str_replace($this->_storeManager->getStore()->getBaseMediaDir(), '', $fullPath)];
                }
            }
        }
        return $options;
    }
}
