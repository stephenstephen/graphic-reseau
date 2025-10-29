<?php
/**
 * Colissimo Shipping Module
 *
 * @author    Magentix
 * @copyright Copyright © 2017 Magentix. All rights reserved.
 * @license   https://www.magentix.fr/en/licence.html Magentix Software Licence
 * @link      https://colissimo.magentix.fr/
 */
namespace Colissimo\Shipping\Api;

/**
 * Interface PickupInterface
 */
interface GuestPickupRepositoryInterface
{

    /**
     * Retrieve current pickup data for quote
     *
     * @param string $cartId
     * @return \Colissimo\Shipping\Api\Data\PickupInterface
     */
    public function current($cartId);

    /**
     * Save pickup data for quote
     *
     * @param string $cartId
     * @param string $pickupId
     * @param string $networkCode
     * @param string $telephone
     * @return bool
     */
    public function save($cartId, $pickupId, $networkCode, $telephone);

    /**
     * Reset pickup data for quote
     *
     * @param string $cartId
     * @return bool
     */
    public function reset($cartId);
}
