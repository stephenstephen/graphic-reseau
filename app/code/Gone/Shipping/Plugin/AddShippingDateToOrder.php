<?php
/*
 * Copyright © 410 Gone (contact@410-gone.fr). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace Gone\Shipping\Plugin;

use Gone\Shipping\Helper\Date;
use Gone\Shipping\Helper\ShippingDelaysHelper;
use Magento\Quote\Api\ShippingMethodManagementInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderManagementInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Psr\Log\LoggerInterface;

class AddShippingDateToOrder
{

    protected OrderRepositoryInterface $_orderRepository;
    protected ShippingMethodManagementInterface $_shippingMethodRepository;
    protected ShippingDelaysHelper $_shippingDelayHelper;
    protected Date $_dateHelper;
    protected LoggerInterface $logger;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        ShippingMethodManagementInterface $shippingMethodRepository,
        ShippingDelaysHelper $shippingDelayHelper,
        Date $dateHelper,
        LoggerInterface $logger
    ) {
        $this->_orderRepository = $orderRepository;
        $this->_shippingMethodRepository = $shippingMethodRepository;
        $this->_dateHelper = $dateHelper;
        $this->_shippingDelayHelper = $shippingDelayHelper;
        $this->logger = $logger;
    }

    /**
     * @param OrderManagementInterface $orderManagementInterface
     * @param OrderInterface $order
     * @return OrderInterface
     */
    public function beforePlace(
        OrderManagementInterface $subject,
        OrderInterface $order
    ) {
        try {
            $shippingMethodName = $order->getShippingMethod();
            $shippingMethodList = $this->_shippingMethodRepository->getList($order->getQuoteId());
            foreach ($shippingMethodList as $method) {
                $methodFullCode = $method->getCarrierCode() . '_' . $method->getMethodCode();
                if ($shippingMethodName == $methodFullCode) {
                    $maxDelayProduct = $this->_dateHelper->getMaxProductDelayOrder($order);

                    $delayString = $this->_shippingDelayHelper->getShippingDelay(
                        $method,
                        $maxDelayProduct,
                        'Y-m-d H:i:s'
                    );
                    $order->setGrEstimatedShippingDate($delayString);
                }
            }
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }

        return [$order];
    }
}
