<?php

namespace AtooSync\GesCom\Model\Config\Backend;

use Magento\Framework\Option\ArrayInterface;

class RoundingPrecisionType implements ArrayInterface
{
    public function toOptionArray()
    {
        $RoundingPrecisionType = array();

        $RoundingPrecisionType[] = [
            'value' => '2',
            'label' => __('2 decimal places')
        ];
        $RoundingPrecisionType[] = [
            'value' => '3',
            'label' => __('3 decimal places')
        ];
        
        $RoundingPrecisionType[] = [
            'value' => '4',
            'label' => __('4 decimal places')
        ];
        
        return $RoundingPrecisionType;
    }
}