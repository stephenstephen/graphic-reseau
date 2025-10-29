<?php

namespace AtooSync\GesCom\Model\Config\Backend;

use Magento\Framework\Option\ArrayInterface;

class CustomerPassword implements ArrayInterface
{
    public function toOptionArray()
    {
        $CustomerPassword = array();

        $CustomerPassword[] = [
            'value' => 'AccountNumber',
            'label' => __('Account number')
        ];
        $CustomerPassword[] = [
            'value' => 'Random10',
            'label' => __('Random10')
        ];
        
               
        return $CustomerPassword;
    }
}