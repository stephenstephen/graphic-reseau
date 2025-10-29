<?php
/**
 * Colissimo Shipping Module
 *
 * @author    Magentix
 * @copyright Copyright © 2019 Magentix. All rights reserved.
 * @license   https://www.magentix.fr/en/licence.html Magentix Software Licence
 * @link      https://colissimo.magentix.fr/
 */
namespace Colissimo\Shipping\Model\ResourceModel;

use Colissimo\Shipping\Api\Data\PriceInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\DataObject;

/**
 * Class Price
 */
class Price extends AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     * @phpcs:disable
     */
    protected function _construct()
    {
        $this->_init('colissimo_shipping_tablerate', 'pk');
    }

    /**
     * Truncate all prices
     *
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function truncate()
    {
        $this->getConnection()->truncateTable($this->getMainTable());

        return true;
    }

    /**
     * Perform actions before object save
     *
     * @param AbstractModel|DataObject $object
     * @return $this
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @phpcs:disable
     */
    protected function _beforeSave(AbstractModel $object)
    {
        if (empty($object->getData(PriceInterface::WEIGHT_FROM))) {
            $object->setData(PriceInterface::WEIGHT_FROM, null);
        }
        if (empty($object->getData(PriceInterface::WEIGHT_TO))) {
            $object->setData(PriceInterface::WEIGHT_TO, null);
        }
        if (empty($object->getData(PriceInterface::PRICE))) {
            $object->setData(PriceInterface::PRICE, 0);
        }
        if (empty($object->getData(PriceInterface::STORE_ID))) {
            $object->setData(PriceInterface::STORE_ID, 0);
        }
        return $this;
    }
}
