<?php

namespace Gone\Checkout\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Model\Order\Email\Sender\ShipmentSender;
class AddCustomVariableToShipmentMailObserver implements ObserverInterface
{
    /**
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {

        $sender = $observer->getData('sender');
        if (!$sender instanceof ShipmentSender) {
            return;
        }

        /** @var DataObject $transport */
        $transport = $observer->getData('transportObject');
        $order = $transport['order'];
        $transport->setData('is_owebia_entrepot', $order->getShippingMethod() === 'owsh1_id_8');
        return;
    }
}
