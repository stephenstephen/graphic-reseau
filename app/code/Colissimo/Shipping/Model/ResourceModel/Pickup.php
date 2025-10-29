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

use Colissimo\Shipping\Api\Data\ShippingDataInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Sales\Api\Data\OrderAddressInterface;
use Magento\Sales\Model\Order\Address;

class Pickup extends AbstractDb
{
    /**
     * Prefix for resources that will be used in this resource model
     *
     * @var string
     */
    protected $connectionName = 'checkout';

    /**
     * Model initialization
     *
     * @return void
     * @phpcs:disable
     */
    protected function _construct()
    {
        $this->_init('quote_colissimo_pickup', 'quote_id');
    }

    /**
     * Save pickup data for quote
     *
     * @param string   $cartId
     * @param string   $pickupId
     * @param string   $networkCode
     * @param string   $telephone
     * @param string[] $address
     *
     * @return bool
     * @throws LocalizedException
     */
    public function savePickup($cartId, $pickupId, $networkCode, $telephone, $address)
    {
        $connection = $this->getConnection();

        $data = [
            'quote_id'     => $cartId,
            'pickup_id'    => $pickupId,
            'network_code' => $networkCode,
            'telephone'    => $telephone,
        ];

        $data = array_merge($address, $data);

        $connection->insertOnDuplicate(
            $this->getMainTable(),
            $data,
            array_keys($data)
        );

        return true;
    }

    /**
     * Retrieve current pickup for quote
     *
     * @param string|int $cartId
     *
     * @return string[]|false
     * @throws LocalizedException
     */
    public function currentPickup($cartId)
    {
        $connection = $this->getConnection();

        return $connection->fetchRow(
            $connection->select()
                ->from(
                    $this->getMainTable(),
                    [
                        'pickup_id',
                        'network_code',
                        'pickup_type',
                        'telephone',
                        'company',
                        'street',
                        'postcode',
                        'city',
                        'country_id'
                    ]
                )
                ->where('quote_id = ?', $cartId)
                ->limit(1)
        );
    }

    /**
     * Reset pickup data for quote
     *
     * @param string $cartId
     * @return bool
     * @throws LocalizedException
     */
    public function resetPickup($cartId)
    {
        $connection = $this->getConnection();

        $connection->delete(
            $this->getMainTable(),
            [
                'quote_id = ?' => $cartId
            ]
        );

        return true;
    }

    /**
     * Retrieve shipping data for order
     *
     * @param int $orderId
     * @return array
     */
    public function shippingData($orderId)
    {
        $connection = $this->getConnection();

        $data = $connection->fetchRow(
            $connection->select()
                ->from(
                    $this->getTable('sales_order_address'),
                    [
                        ShippingDataInterface::COLISSIMO_PRODUCT_CODE,
                        ShippingDataInterface::COLISSIMO_PICKUP_ID,
                        ShippingDataInterface::COLISSIMO_NETWORK_CODE
                    ]
                )
                ->where(OrderAddressInterface::PARENT_ID . ' = ?', $orderId)
                ->where(OrderAddressInterface::ADDRESS_TYPE . ' = ?', Address::TYPE_SHIPPING)
                ->limit(1)
        );

        return $data;
    }
}
