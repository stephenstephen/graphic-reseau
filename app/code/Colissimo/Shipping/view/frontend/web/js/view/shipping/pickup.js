/*global define*/
define([
    'jquery',
    'uiComponent',
    'ko',
    'Colissimo_Shipping/js/lib/popup',
    'Colissimo_Shipping/js/model/shipping/pickup',
    'Colissimo_Shipping/js/view/checkout/address',
    'Magento_Checkout/js/model/step-navigator',
    'Magento_Checkout/js/action/set-shipping-information',
    'Magento_Checkout/js/model/quote',
    'mage/translate'
], function (
    $,
    Component,
    ko,
    popup,
    pickupModel,
    pickupAddress,
    stepNavigator,
    setShippingInformationAction,
    quote
) {
    'use strict';

    return Component.extend({
        actions:{
            'load':window.checkoutConfig.colissimoUrl + 'pickup/load'
        },

        initialize: function () {
            this._super();
        },

        /**
         * Run
         */
        run: function () {
            popup.open(920, 595);
            this.pickupAction();
        },

        /**
         * Load pop-up content with Ajax Request
         *
         * @param {string} action
         * @param {Object} data
         */
        loadContent: function (action, data) {
            popup.closeMessage();
            $.ajax({
                url: action,
                type: 'post',
                context: this,
                data: data,
                beforeSend: popup.loader($.mage.__('Loading...')),
                success: function (response) {
                    popup.update(response);
                }
            });
        },

        /**
         * Launch pickup action
         */
        pickupAction: function () {
            var address = quote.shippingAddress();
            var data = {};
            if (address) {
                if (address.street) {
                    if (address.street.length) {
                        data.street = address.street[0];
                    }
                }
                if (address.city) {
                    data.city = address.city;
                }
                if (address.postcode) {
                    data.postcode = address.postcode;
                }
                if (address.countryId && address.countryId !== 'US') {
                    data.country_id = address.countryId;
                }
                if (address.telephone) {
                    data.telephone = address.telephone;
                }
            }

            this.loadContent(this.actions.load, data);
        },

        /**
         * Init Pickup action
         *
         * @param {Object.<number, Object>} locations
         * @param {Object} maps
         * @param {string} phoneRegex
         * @param {string} phoneCode
         */
        pickupInit: function (locations, maps, phoneRegex, phoneCode) {
            var pickup = this;

            /* Form Pickup */
            $('#sc-pickup').submit(function (event) {
                event.preventDefault();
                var checked = $(this).find("input[name=pickup]:checked");
                if (!checked.length) {
                    popup.error($.mage.__('Please select pickup'));
                    return false;
                }

                var input = $(this).find("input[name=telephone]");
                if (!input) {
                    popup.error($.mage.__('Please enter a valid mobile phone number'));
                    return false;
                }

                var telephone = phoneCode + input.val().replace(/\D/g, '');
                if (phoneRegex) {
                    if (!phoneRegex.test(telephone)) {
                        popup.error($.mage.__('Please enter a valid mobile phone number'));
                        input.addClass('error');
                        return false;
                    }
                }

                popup.loader($.mage.__('Loading...'));

                var pickupData = checked.val().split('-');
                pickup.pickupUpdateQuote(pickupData[0], pickupData[1], telephone);
            });

            /* Form Address */
            $('#sc-address').submit(function (event) {
                pickup.loadContent(pickup.actions.load, $(this).serializeArray());
                event.preventDefault();
            });

            /* Back button */
            $('#sc-previous').click(function (event) {
                popup.close();
                event.preventDefault();
            });

            /* Select pickup */
            $('#sc-list').find('input').click(function () {
                $('#sc-list').find('li').removeClass('active');
                $(this).parent('li').addClass('active');
                maps.update($(this).attr('id'));
            });

            /* Show info */
            $('.colissimo-show-info').click(function (event) {
                popup.message($(this).parent('label').next('div').html(), false);
                $(popup.PopupMessage).find('button').click(function () {
                    popup.closeMessageWithEffect();
                });
                event.preventDefault();
            });

            maps.run('sc-map', 'sc-list');
            maps.locations(locations);
            var address = '';
            $('#sc-address').find('input').each(function () {
                address += $(this).val() + ' ';
            });
            if (address) {
                maps.address(address);
            }
        },

        pickupUpdateQuote: function (pickupId, networkCode, telephone) {
            var pickup = this;

            if (typeof pickupId === 'undefined') {
                pickupId = null;
            }

            if (typeof networkCode === 'undefined') {
                networkCode = null;
            }

            if (typeof telephone === 'undefined') {
                telephone = null;
            }

            if (pickupId && networkCode) {
                var address = pickupModel.getPickup(pickupId, networkCode);
                address.done(
                    function (data) {
                        pickupModel.savePickup(quote.getQuoteId(), pickupId, networkCode, telephone);
                        pickupAddress.pickupAddress(data);
                        pickup.pickupUpdateAddress();
                        popup.close();
                        if (window.checkoutConfig.colissimoOpen === '0' && stepNavigator.getActiveItemIndex() === 0) {
                            stepNavigator.next();
                        }
                    }
                ).fail(
                    function () {
                        pickupAddress.pickupAddress('');
                        popup.error($.mage.__('Unable to load pickup, please select another shipping method'));
                    }
                );
            }
        },

        pickupUpdateAddress: function () {
            var pickup = this;

            var label = $('#label_method_pickup_colissimo');

            if (label.length) {
                if (!$('#colissimo_pickup_address').length) {
                    label.parent('tr').after(
                        '<tr id="colissimo_pickup_address">' +
                            '<td id="colissimo_pickup_address_content" colspan="4"></td>' +
                        '</tr>'
                    );

                    /* Compatibility with Aheadworks_OneStepCheckout */
                    label.next('.shipping-method-subtitle').append(
                        '<div id="colissimo_pickup_address">' +
                            '<span id="colissimo_pickup_address_content"></span>' +
                        '</div>'
                    );
                }

                ko.utils.setHtml(
                    $('#colissimo_pickup_address_content'),
                    $('#colissimo-pickup-selected').html()
                );

                $('.sc-update-pickup').click(function (event) {
                    pickup.run();
                    event.preventDefault();
                });
            }
        },

        pickupRemoveAddress: function (resetPickup) {
            if ($('#colissimo_pickup_address').length) {
                $('#colissimo_pickup_address').remove();
            }
            pickupAddress.pickupAddress('');
            if (resetPickup) {
                pickupModel.resetPickup(quote.getQuoteId());
            }
        }
    });
});