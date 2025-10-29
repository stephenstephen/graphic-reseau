define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/shipping-rates-validator',
        'Magento_Checkout/js/model/shipping-rates-validation-rules',
        '../model/shipping-rates-validator',
        '../model/shipping-rates-validation-rules'
    ],
    function (
        Component,
        defaultShippingRatesValidator,
        defaultShippingRatesValidationRules,
        chronopostShippingRatesValidator,
        chronopostShippingRatesValidationRules
    ) {
        "use strict";

        defaultShippingRatesValidator.registerValidator('chronopost', chronopostShippingRatesValidator);
        defaultShippingRatesValidationRules.registerRules('chronopost', chronopostShippingRatesValidationRules);

        return Component;
    }
);
