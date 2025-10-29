define([
    'uiComponent',
    'Magento_Checkout/js/model/payment/additional-validators',
    'Colissimo_Shipping/js/model/order-validator'
], function (
    Component,
    additionalValidators,
    orderValidator
) {
    'use strict';

    additionalValidators.registerValidator(orderValidator);
    return Component.extend({});
});