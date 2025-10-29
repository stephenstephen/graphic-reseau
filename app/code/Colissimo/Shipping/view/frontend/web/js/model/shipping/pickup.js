/*global define*/
define([
    'jquery',
    'Magento_Checkout/js/model/url-builder',
    'Magento_Customer/js/model/customer',
    'mage/storage'
], function ($, urlBuilder, customer, storage) {
    'use strict';

    return {
        getUrlForRetrievePickupAddress: function (pickupId, networkCode) {
            return urlBuilder.createUrl(
                '/colissimoPickup/:pickupId/:networkCode',
                {'pickupId':pickupId, 'networkCode':networkCode}
            );
        },

        getUrlForSavePickup: function (quoteId, pickupId, networkCode, telephone) {
            telephone = encodeURIComponent(telephone);
            var url = urlBuilder.createUrl(
                '/carts/mine/colissimo-pickup/:pickupId/:networkCode/:telephone',
                {'pickupId':pickupId, 'networkCode':networkCode, 'telephone':telephone}
            );

            if (this.isGuest()) {
                url = urlBuilder.createUrl(
                    '/guest-carts/:cartId/colissimo-pickup/:pickupId/:networkCode/:telephone',
                    {'cartId':quoteId, 'pickupId':pickupId, 'networkCode':networkCode, 'telephone':telephone}
                );
            }
            return url;
        },

        getUrlForCurrentPickup: function (quoteId) {
            var url = urlBuilder.createUrl('/carts/mine/colissimo-pickup', {});

            if (this.isGuest()) {
                url = urlBuilder.createUrl('/guest-carts/:cartId/colissimo-pickup', {'cartId':quoteId});
            }
            return url;
        },

        getUrlForResetPickup: function (quoteId) {
            var url = urlBuilder.createUrl('/carts/mine/colissimo-pickup', {});

            if (this.isGuest()) {
                url = urlBuilder.createUrl('/guest-carts/:cartId/colissimo-pickup', {'cartId':quoteId});
            }
            return url;
        },

        getPickup: function (pickupId, networkCode) {
            return storage.get(this.getUrlForRetrievePickupAddress(pickupId, networkCode), false);
        },

        currentPickup: function (quoteId) {
            return storage.get(this.getUrlForCurrentPickup(quoteId), false);
        },

        savePickup: function (quoteId, pickupId, networkCode, telephone) {
            return storage.put(this.getUrlForSavePickup(quoteId, pickupId, networkCode, telephone), false);
        },

        resetPickup: function (quoteId) {
            return storage.delete(this.getUrlForResetPickup(quoteId), false);
        },

        isGuest: function () {
            return !customer.isLoggedIn();
        }
    }
});