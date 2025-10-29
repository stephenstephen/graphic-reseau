<?php

namespace Gone\Checkout\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

class AddCustomVariableToMailObserver implements ObserverInterface
{

    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    protected $_timezone;

    public function __construct(
        TimezoneInterface $timezone
    )
    {
        $this->_timezone = $timezone;
    }

    /**
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        /** @var \Magento\Framework\App\Action\Action $controller */
        $transport = $observer->getEvent()->getTransport();
        $order = $transport->getOrder();

        $transport['gr_estimated_shipping_date'] = $this->_timezone
            ->date(new \DateTime($order->getGrEstimatedShippingDate()))
            ->format('d-m-Y');
    }
}
