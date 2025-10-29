<?php

namespace AtooSync\GesCom\Model\Config\Backend;

use Magento\Framework\Option\ArrayInterface;

class HttpHeader implements ArrayInterface
{
    public function toOptionArray()
    {
        $HttpHeader = array();

        $HttpHeader[] = [
            'value' => 'XML',
            'label' => __('Content-type: text/xml')
        ];
        $HttpHeader[] = [
            'value' => 'HTTP',
            'label' => __('Content-type: text/html')
        ];
               
        return $HttpHeader;
    }
}