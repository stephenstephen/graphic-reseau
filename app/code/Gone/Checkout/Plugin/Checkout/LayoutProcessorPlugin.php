<?php

namespace Gone\Checkout\Plugin\Checkout;

use Magento\Checkout\Block\Checkout\LayoutProcessor;

class LayoutProcessorPlugin
{
    /**
     * @param LayoutProcessor $subject
     * @param array $jsLayout
     * @return array
     */
    public function afterProcess(
        LayoutProcessor $subject,
        array $jsLayout
    ) {

        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['before-form']['children']['gr_customer_shipment_comment'] = [
            'component' => 'Magento_Ui/js/form/element/textarea',
            'config' => [
                'customScope' => 'shippingAddress',
                'template' => 'ui/form/field',
                'options' => [],
                'id' => 'gr_customer_shipment_comment'
            ],
            'dataScope' => 'shippingAddress.gr_customer_shipment_comment',
            'label' => __('Shipment Comments'),
            'provider' => 'checkoutProvider',
            'visible' => true,
            'id' => 'gr_customer_shipment_comment'
        ];

        return $jsLayout;
    }
}
