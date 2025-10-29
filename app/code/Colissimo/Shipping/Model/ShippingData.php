<?php
/**
 * Colissimo Shipping Module
 *
 * @author    Magentix
 * @copyright Copyright © 2018 Magentix. All rights reserved.
 * @license   https://www.magentix.fr/en/licence.html Magentix Software Licence
 * @link      https://colissimo.magentix.fr/
 */
namespace Colissimo\Shipping\Model;

use Colissimo\Shipping\Api\Data\ShippingDataInterface;
use Magento\Framework\DataObject;

/**
 * Class Pickup
 */
class ShippingData extends DataObject implements ShippingDataInterface
{
    /**
     * Get product Code
     *
     * @return string
     */
    public function getColissimoProductCode()
    {
        return $this->getData(self::COLISSIMO_PRODUCT_CODE);
    }

    /**
     * Get Pickup Id
     *
     * @return string
     */
    public function getColissimoPickupId()
    {
        return $this->getData(self::COLISSIMO_PICKUP_ID);
    }

    /**
     * Get Network Code
     *
     * @return string
     */
    public function getColissimoNetworkCode()
    {
        return $this->getData(self::COLISSIMO_NETWORK_CODE);
    }
}
