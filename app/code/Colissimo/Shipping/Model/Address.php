<?php
/**
 * Colissimo Shipping Module
 *
 * @author    Magentix
 * @copyright Copyright © 2017 Magentix. All rights reserved.
 * @license   https://www.magentix.fr/en/licence.html Magentix Software Licence
 * @link      https://colissimo.magentix.fr/
 */
namespace Colissimo\Shipping\Model;

use Colissimo\Shipping\Helper\Data as ShippingHelper;
use Colissimo\Shipping\Model\Carrier\Colissimo;
use Colissimo\Shipping\Api\Data\ShippingDataInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Quote\Model\Quote;
use Magento\Sales\Model\Order;

/**
 * Class Pickup
 */
class Address extends DataObject
{
    /**
     * @var ShippingHelper $shippingHelper
     */
    protected $shippingHelper;

    /**
     * @var Pickup $pickup
     */
    protected $pickup;

    /**
     * @param ShippingHelper $shippingHelper
     * @param Pickup $pickup
     * @param array $data
     */
    public function __construct(
        ShippingHelper $shippingHelper,
        Pickup $pickup,
        array $data = []
    ) {
        $this->pickup = $pickup;
        $this->shippingHelper = $shippingHelper;
        parent::__construct($data);
    }

    /**
     * Update Shipping Address
     *
     * @param Order $order
     * @param Quote $quote
     *
     * @return $this
     * @throws LocalizedException
     */
    public function updateShippingAddress($order, $quote)
    {
        if (!$order) {
            return $this;
        }

        if (!$quote) {
            return $this;
        }

        $address = $order->getShippingAddress();

        if (!$address) {
            return $this;
        }

        if ($address->getAddressType() !== 'shipping') {
            return $this;
        }

        $shippingMethod = $quote->getShippingAddress()->getShippingMethod();

        if (!$shippingMethod) {
            return $this;
        }

        list($code, $method) = explode('_', $shippingMethod);

        /* Reset Data */
        $address->setData(ShippingDataInterface::COLISSIMO_PRODUCT_CODE, null);
        $address->setData(ShippingDataInterface::COLISSIMO_PICKUP_ID, null);
        $address->setData(ShippingDataInterface::COLISSIMO_NETWORK_CODE, null);

        if ($code !== Colissimo::SHIPPING_CARRIER_CODE) {
            return $this;
        }

        /* Set code */
        $productCode = $this->shippingHelper->getConfig($method . '/product_code');
        if ($productCode) {
            $address->setData(ShippingDataInterface::COLISSIMO_PRODUCT_CODE, $productCode);
        }

        /* Set pickup data */
        $pickup = $this->pickup->getPickupAddress($quote->getId());

        if (is_array($pickup)) {
            $address->setCompany($pickup['company'])
                ->setStreet([$pickup['street']])
                ->setPostcode($pickup['postcode'])
                ->setCity($pickup['city'])
                ->setCountryId($pickup['country_id'])
                ->setFax('')
                ->setCustomerAddressId(null)
                ->setData(ShippingDataInterface::COLISSIMO_PICKUP_ID, $pickup['pickup_id'])
                ->setData(ShippingDataInterface::COLISSIMO_PRODUCT_CODE, $pickup['pickup_type'])
                ->setData(ShippingDataInterface::COLISSIMO_NETWORK_CODE, $pickup['network_code'])
                ->setSameAsBilling(0)
                ->setSaveInAddressBook(0);

            $region = $this->shippingHelper->getRegion($pickup['country_id'], $pickup['postcode']);
            if ($region->hasData()) {
                $address->setRegion($region->getDefaultName())
                    ->setRegionId($region->getRegionId())
                    ->setRegionCode($region->getCode());
            }

            if ($pickup['telephone']) {
                $address->setTelephone($pickup['telephone']);
            }

            // Do not delete data if there is an error in the order
            // $this->pickup->reset($quote->getId());
        }

        return $this;
    }
}
