<?php
namespace Chronopost\Chronorelais\Block\Head;

use Magento\Framework\View\Element\Template;

/**
 * Class GoogleMap
 * @package Chronopost\Chronorelais\Block\Head
 */
class GoogleMap extends Template
{
    public function getApiKey() {
        return $this->_scopeConfig->getValue("chronorelais/shipping/google_map_api");
    }
}