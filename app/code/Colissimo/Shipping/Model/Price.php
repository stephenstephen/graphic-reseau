<?php
/**
 * Colissimo Shipping Module
 *
 * @author    Magentix
 * @copyright Copyright © 2019 Magentix. All rights reserved.
 * @license   https://www.magentix.fr/en/licence.html Magentix Software Licence
 * @link      https://colissimo.magentix.fr/
 */
namespace Colissimo\Shipping\Model;

use Colissimo\Shipping\Api\Data\PriceInterface;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * Class Price
 */
class Price extends AbstractModel implements PriceInterface, IdentityInterface
{
    /**
     * Prefix of model events names
     *
     * @var string
     * @phpcs:disable
     */
    protected $_eventPrefix = 'colissimo_price';

    /**
     * Cache tag
     */
    const CACHE_TAG = 'colissimo_price';

    /**
     * Initialize resource model
     *
     * @return void
     * @phpcs:disable
     */
    protected function _construct()
    {
        $this->_init('Colissimo\Shipping\Model\ResourceModel\Price');
    }

    /**
     * Return unique ID(s) for each object in system
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getPk()];
    }

    /**
     * @return int
     */
    public function getPk()
    {
        return $this->getData(self::PK);
    }

    /**
     * @return int
     */
    public function getStoreId()
    {
        return $this->getData(self::STORE_ID);
    }

    /**
     * @return string
     */
    public function getCountryId()
    {
        return $this->getData(self::COUNTRY_ID);
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->getData(self::PRICE);
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->getData(self::METHOD);
    }

    /**
     * @return float
     */
    public function getWeightFrom()
    {
        return $this->getData(self::WEIGHT_FROM);
    }

    /**
     * @return float
     */
    public function getWeightTo()
    {
        return $this->getData(self::WEIGHT_TO);
    }

    /**
     * @return bool
     */
    public function getIsActive()
    {
        return $this->getData(self::IS_ACTIVE);
    }

    /**
     * Set Pk
     *
     * @param int $pk
     * @return \Colissimo\Shipping\Api\Data\PriceInterface
     */
    public function setPk($pk)
    {
        return $this->setData(self::PK, $pk);
    }

    /**
     * Set Store Id
     *
     * @param int $storeId
     * @return \Colissimo\Shipping\Api\Data\PriceInterface
     */
    public function setStoreId($storeId)
    {
        return $this->setData(self::STORE_ID, $storeId);
    }

    /**
     * Set Country Id
     *
     * @param string $countryId
     * @return \Colissimo\Shipping\Api\Data\PriceInterface
     */
    public function setCountryId($countryId)
    {
        return $this->setData(self::COUNTRY_ID, $countryId);
    }

    /**
     * Set Price
     *
     * @param float $price
     * @return \Colissimo\Shipping\Api\Data\PriceInterface
     */
    public function setPrice($price)
    {
        return $this->setData(self::PRICE, $price);
    }

    /**
     * Set Method
     *
     * @param string $method
     * @return \Colissimo\Shipping\Api\Data\PriceInterface
     */
    public function setMethod($method)
    {
        return $this->setData(self::METHOD, $method);
    }

    /**
     * Set Weight From
     *
     * @param float $weightFrom
     * @return \Colissimo\Shipping\Api\Data\PriceInterface
     */
    public function setWeightFrom($weightFrom)
    {
        return $this->setData(self::WEIGHT_FROM, $weightFrom);
    }

    /**
     * Set Weight To
     *
     * @param float $weightTo
     * @return \Colissimo\Shipping\Api\Data\PriceInterface
     */
    public function setWeightTo($weightTo)
    {
        return $this->setData(self::WEIGHT_TO, $weightTo);
    }

    /**
     * Set Is Active
     *
     * @param bool $isActive
     * @return \Colissimo\Shipping\Api\Data\PriceInterface
     */
    public function setIsActive($isActive)
    {
        return $this->setData(self::IS_ACTIVE, $isActive);
    }
}
