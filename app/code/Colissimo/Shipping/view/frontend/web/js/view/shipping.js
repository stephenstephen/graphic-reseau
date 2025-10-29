/*global define*/
define(
    [
        'Magento_Checkout/js/action/set-shipping-information',
        'Magento_Checkout/js/model/quote',
        'Magento_Checkout/js/model/step-navigator',
        'Colissimo_Shipping/js/view/shipping/pickup',
        'Colissimo_Shipping/js/view/checkout/address',
        'Colissimo_Shipping/js/model/shipping/pickup'
    ],
    function (
        setShippingInformationAction,
        quote,
        stepNavigator,
        pickupView,
        address,
        pickupModel
    ) {
        'use strict';

        return function (target) {
            return target.extend({
                setShippingInformation: function () {
                    var method = null;
                    if (quote.shippingMethod()) {
                        method = quote.shippingMethod().carrier_code + '_' + quote.shippingMethod().method_code;
                    }

                    var isColissimoPickup = method === window.checkoutConfig.colissimoPickup;

                    if (method && !isColissimoPickup) {
                        pickupView.prototype.pickupRemoveAddress(true);
                    }

                    if (method && isColissimoPickup && address.pickupAddress()) {
                        pickupModel.currentPickup(quote.getQuoteId()).complete(function (object) {
                            var pickup = object.responseJSON;
                            if (!pickup.identifiant) {
                                address.pickupAddress('')
                            }
                        });
                    }

                    if (method && isColissimoPickup && !address.pickupAddress()) {
                        if (this.validateShippingInformation()) {
                            setShippingInformationAction().done(
                                function () {
                                    pickupView.prototype.run();
                                }
                            );
                        }
                    } else {
                        this._super();
                    }
                }
            });
        }
    }
);