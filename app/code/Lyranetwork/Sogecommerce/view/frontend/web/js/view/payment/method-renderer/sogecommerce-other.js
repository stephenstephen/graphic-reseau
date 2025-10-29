/**
 * Copyright © Lyra Network.
 * This file is part of Sogecommerce plugin for Magento 2. See COPYING.md for license details.
 *
 * @author    Lyra Network (https://www.lyra.com/)
 * @copyright Lyra Network
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */

/*browser:true*/
/*global define*/
define(
    [
        'Lyranetwork_Sogecommerce/js/view/payment/method-renderer/sogecommerce-abstract',
        'Magento_Checkout/js/action/select-payment-method',
        'Magento_Checkout/js/checkout-data'
    ],
    function(
        Component,
        selectPaymentMethodAction,
        checkoutData
    ) {
        'use strict';

        return Component.extend({
            defaults: {
                template: 'Lyranetwork_Sogecommerce/payment/sogecommerce-other',
                sogecommerceOtherOption: window.checkoutConfig.payment.sogecommerce_other.availableOptions ?
                        window.checkoutConfig.payment.sogecommerce_other.availableOptions[0]['key'] : null,
            },

            initObservable: function() {
                this._super();
                this.observe('sogecommerceOtherOption');

                return this;
            },

            getData: function() {
                var data = this._super();

                data['additional_data']['sogecommerce_other_option'] = this.sogecommerceOtherOption();

                return data;
            },

            /**
             * Get payment method code
             */
            getOptionCode: function(option) {
                return this.getCode() + '_' + option;
            },

            /**
             * Get payment method data
             */
            getOptionData: function(method) {
                var data = this.getData();
                data['method'] =  method;

                return data;
            },

            selectOptionPaymentMethod: function(option) {
                var method = this.getCode() + '_' + option;

                selectPaymentMethodAction(this.getOptionData('sogecommerce_other'));
                checkoutData.setSelectedPaymentMethod(method);

                return true;
            },

            showLabel: function() {
                return true;
            },

            getAvailableOptions: function() {
                return window.checkoutConfig.payment.sogecommerce_other.availableOptions;
            },

            getRegroupMode: function() {
                return window.checkoutConfig.payment.sogecommerce_other.regroupMode;
            },

            sogecommerceOptionChecked: function() {
                return checkoutData.getSelectedPaymentMethod();
            }
        });
    }
);
