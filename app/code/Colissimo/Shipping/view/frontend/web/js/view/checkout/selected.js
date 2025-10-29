/*global define*/
define(
    [
        'Magento_Checkout/js/view/summary/abstract-total',
        'ko',
        'Colissimo_Shipping/js/view/checkout/address',
        'Colissimo_Shipping/js/view/shipping/pickup',
        'Magento_Checkout/js/model/quote'
    ],
    function (
        Component,
        ko,
        address,
        pickupView,
        quote
    ) {
        'use strict';
        return Component.extend({
            address: address.pickupAddress,
            totals: quote.getTotals(),
            defaults: {
                template: 'Colissimo_Shipping/checkout/selected'
            },

            initialize: function () {
                this._super();
            },

            getPickupAddress: function () {
                return address.pickupAddress();
            },

            updatePickupAddress: function () {
                pickupView.prototype.run();
            }
        });
    }
);