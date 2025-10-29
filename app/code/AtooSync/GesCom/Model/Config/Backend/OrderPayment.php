<?php

namespace AtooSync\GesCom\Model\Config\Backend;

use Magento\Framework\Option\ArrayInterface;

class OrderPayment implements ArrayInterface
{
    public function toOptionArray()
    {
        $OrderPayment = array();

        $OrderPayment[] = [
            'value' => 'module',
            'label' => __('Payment modules installed')
        ];
        $OrderPayment[] = [
            'value' => 'order_module',
            'label' => __('Payments modules of orders')
        ];
        $OrderPayment[] = [
            'value' => 'order_payment',
            'label' => __('Settlements name of orders.')
        ];
        return $OrderPayment;
    }
}