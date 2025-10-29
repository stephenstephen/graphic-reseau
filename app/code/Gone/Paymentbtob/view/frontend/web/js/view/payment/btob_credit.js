define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ],
    function (
        Component,
        rendererList
    ) {
        'use strict';
        rendererList.push(
            {
                type: 'btob_credit',
                component: 'Gone_Paymentbtob/js/view/payment/method-renderer/btob_credit-method'
            }
        );
        return Component.extend({});
    }
);