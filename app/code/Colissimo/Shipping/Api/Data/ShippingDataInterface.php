<?php
/**
 * Colissimo Shipping Module
 *
 * @author    Magentix
 * @copyright Copyright © 2018 Magentix. All rights reserved.
 * @license   https://www.magentix.fr/en/licence.html Magentix Software Licence
 * @link      https://colissimo.magentix.fr/
 */
namespace Colissimo\Shipping\Api\Data;

/**
 * Interface which represents associative array item.
 */
interface ShippingDataInterface
{

    const COLISSIMO_PRODUCT_CODE = 'colissimo_product_code';

    const COLISSIMO_PICKUP_ID = 'colissimo_pickup_id';

    const COLISSIMO_NETWORK_CODE = 'colissimo_network_code';

    /**
     * Get product Code
     *
     * @return string
     */
    public function getColissimoProductCode();

    /**
     * Get Pickup Id
     *
     * @return string
     */
    public function getColissimoPickupId();

    /**
     * Get Network Code
     *
     * @return string
     */
    public function getColissimoNetworkCode();
}
