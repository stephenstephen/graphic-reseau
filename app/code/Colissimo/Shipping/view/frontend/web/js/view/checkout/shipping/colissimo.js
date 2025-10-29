/*global define*/
define([
    'uiComponent',
    'Magento_Checkout/js/model/quote',
    'Colissimo_Shipping/js/view/shipping/pickup',
    'Colissimo_Shipping/js/view/checkout/address',
    'Colissimo_Shipping/js/model/shipping/pickup'
], function (Component, quote, pickupView, pickupAddress, pickupModel) {
    'use strict';

    return Component.extend({
        shippingMethod: quote.shippingMethod,

        initialize: function () {
            this._super();

            this.shippingMethod.subscribe(function (shippingMethod) {
                if (shippingMethod) {
                    var method = shippingMethod.carrier_code + '_' + shippingMethod.method_code;
                    var isPickup = method === window.checkoutConfig.colissimoPickup;

                    if (!isPickup) {
                        var removeOnShippingSelection = window.checkoutConfig.colissimoRemoveOnShippingSelection;

                        pickupView.prototype.pickupRemoveAddress(!!removeOnShippingSelection);
                    } else {
                        var current = pickupModel.currentPickup(quote.getQuoteId());
                        current.complete(function (object) {
                            var pickup = object.responseJSON;
                            if (pickup.identifiant) {
                                pickupAddress.pickupAddress(pickup);
                                pickupView.prototype.pickupUpdateAddress();
                            }

                            if (!pickupAddress.pickupAddress() && window.checkoutConfig.colissimoOpen === '1') {
                                pickupView.prototype.run();
                            }
                        });
                    }
                }
            });
        }
    });
});