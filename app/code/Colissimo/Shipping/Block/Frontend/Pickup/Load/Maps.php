<?php
/**
 * Colissimo Shipping Module
 *
 * @author    Magentix
 * @copyright Copyright © 2019 Magentix. All rights reserved.
 * @license   https://www.magentix.fr/en/licence.html Magentix Software Licence
 * @link      https://colissimo.magentix.fr/
 */
namespace Colissimo\Shipping\Block\Frontend\Pickup\Load;

use Colissimo\Shipping\Helper\Data as ShippingHelper;
use Magento\Framework\View\Element\Template;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Maps
 */
class Maps extends Template
{
    /**
     * Internal constructor, that is called from real constructor
     * @return void
     * @phpcs:disable
     */
    protected function _construct()
    {
        parent::_construct();

        $this->setTemplate('Colissimo_Shipping::pickup/load/maps/' . $this->getMapType() . '.phtml');
    }

    /**
     * Retrieve map type
     *
     * @return string
     */
    public function getMapType()
    {
        return $this->_scopeConfig->getValue(
            ShippingHelper::COLISSIMO_CONFIG_PREFIX . 'pickup/map_type',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Retrieve Maps API key
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->_scopeConfig->getValue(
            ShippingHelper::COLISSIMO_CONFIG_PREFIX . 'pickup/api_key',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Check if map can be loaded
     *
     * @return mixed
     */
    public function canShow()
    {
        return $this->_scopeConfig->isSetFlag(
            ShippingHelper::COLISSIMO_CONFIG_PREFIX . 'pickup/active',
            ScopeInterface::SCOPE_STORE
        );
    }
}
