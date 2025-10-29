<?php

namespace AtooSync\GesCom\Model\Config\Backend;

use Magento\Framework\Option\ArrayInterface;

class OrderMessage implements ArrayInterface
{
    public function toOptionArray()
    {
        $OrderMessage = array();

        $OrderMessage[] = [
            'value' => 'None',
            'label' => __('No message')
        ];
        $OrderMessage[] = [
            'value' => 'first',
            'label' => __('The first comment')
        ];
        $OrderMessage[] = [
            'value' => 'all',
            'label' => __('All comments.')
        ];
               
        return $OrderMessage;
    }
}