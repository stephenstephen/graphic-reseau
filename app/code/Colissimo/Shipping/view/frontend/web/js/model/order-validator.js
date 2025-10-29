define([
    'jquery',
    'Colissimo_Shipping/js/view/shipping/pickup',
    'Colissimo_Shipping/js/view/checkout/address'
], function (
    $,
    pickupView,
    pickupAddress
) {
    'use strict';

    return {
        pickupInput: 'input[value=colissimo_pickup]:checked',

        /**
         * @returns {boolean}
         */
        validate: function () {
            var isValid = true;

            if ($(this.pickupInput).length) {
                if (!pickupAddress.pickupAddress()) {
                    isValid = false;
                    pickupView.prototype.run();
                }
            }

            return isValid;
        }
    }
});