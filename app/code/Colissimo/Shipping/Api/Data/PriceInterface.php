<?php
/**
 * Colissimo Shipping Module
 *
 * @author    Magentix
 * @copyright Copyright © 2019 Magentix. All rights reserved.
 * @license   https://www.magentix.fr/en/licence.html Magentix Software Licence
 * @link      https://colissimo.magentix.fr/
 */
namespace Colissimo\Shipping\Api\Data;

/**
 * Interface PriceInterface
 */
interface PriceInterface
{
    const PK = 'pk';

    const STORE_ID = 'store_id';

    const COUNTRY_ID = 'country_id';

    const PRICE = 'price';

    const METHOD = 'method';

    const WEIGHT_FROM = 'weight_from';

    const WEIGHT_TO = 'weight_to';

    const IS_ACTIVE = 'is_active';

    /**
     * @return int
     */
    public function getPk();

    /**
     * @return int
     */
    public function getStoreId();

    /**
     * @return string
     */
    public function getCountryId();

    /**
     * @return float
     */
    public function getPrice();

    /**
     * @return string
     */
    public function getMethod();

    /**
     * @return float
     */
    public function getWeightFrom();

    /**
     * @return float
     */
    public function getWeightTo();

    /**
     * @return bool
     */
    public function getIsActive();

    /**
     * Set Pk
     *
     * @param int $pk
     * @return \Colissimo\Shipping\Api\Data\PriceInterface
     */
    public function setPk($pk);

    /**
     * Set Store Id
     *
     * @param int $storeId
     * @return \Colissimo\Shipping\Api\Data\PriceInterface
     */
    public function setStoreId($storeId);

    /**
     * Set Country Id
     *
     * @param string $countryId
     * @return \Colissimo\Shipping\Api\Data\PriceInterface
     */
    public function setCountryId($countryId);

    /**
     * Set Price
     *
     * @param float $price
     * @return \Colissimo\Shipping\Api\Data\PriceInterface
     */
    public function setPrice($price);

    /**
     * Set Method
     *
     * @param string $method
     * @return \Colissimo\Shipping\Api\Data\PriceInterface
     */
    public function setMethod($method);

    /**
     * Set Weight From
     *
     * @param float $weightFrom
     * @return \Colissimo\Shipping\Api\Data\PriceInterface
     */
    public function setWeightFrom($weightFrom);

    /**
     * Set Weight To
     *
     * @param float $weightTo
     * @return \Colissimo\Shipping\Api\Data\PriceInterface
     */
    public function setWeightTo($weightTo);

    /**
     * Set Is Active
     *
     * @param bool $isActive
     * @return \Colissimo\Shipping\Api\Data\PriceInterface
     */
    public function setIsActive($isActive);
}
