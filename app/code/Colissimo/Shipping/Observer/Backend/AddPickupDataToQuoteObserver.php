<?php
/**
 * Colissimo Shipping Module
 *
 * @author    Magentix
 * @copyright Copyright © 2018 Magentix. All rights reserved.
 * @license   https://www.magentix.fr/en/licence.html Magentix Software Licence
 * @link      https://colissimo.magentix.fr/
 */
namespace Colissimo\Shipping\Observer\Backend;

use Colissimo\Shipping\Model\PickupFactory;
use Colissimo\Shipping\Model\Carrier\Colissimo;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;

/**
 * Class AddPickupDataToQuoteObserver
 */
class AddPickupDataToQuoteObserver implements ObserverInterface
{

    /**
     * @var PickupFactory $pickupFactory
     */
    protected $pickupFactory;

    /**
     * @param PickupFactory $pickupFactory
     */
    public function __construct(
        PickupFactory $pickupFactory
    ) {
        $this->pickupFactory = $pickupFactory;
    }

    /**
     * Add data to order address
     *
     * @param Observer $observer
     * @return $this
     */
    public function execute(Observer $observer)
    {
        /** @var \Magento\Sales\Model\AdminOrder\Create $orderCreateModel */
        $orderCreateModel = $observer->getEvent()->getOrderCreateModel();

        /** @var array $request */
        $request = $observer->getEvent()->getRequest();

        $shippingAddress = $orderCreateModel->getShippingAddress();
        $shippingMethod  = $shippingAddress->getShippingMethod();
        $quoteId         = $orderCreateModel->getQuote()->getId();
        $pickup          = $this->pickupFactory->create();

        if ($shippingMethod !== Colissimo::SHIPPING_CARRIER_PICKUP_METHOD) {
            $pickup->reset($quoteId);
        }

        if (isset($request['colissimo']['pickup'])) {
            if ($request['colissimo']['pickup']) {
                list($pickupId, $networkCode) = explode('-', $request['colissimo']['pickup']);
                $pickup->save($quoteId, $pickupId, $networkCode, $shippingAddress->getTelephone());
            }
        }

        return $this;
    }
}
