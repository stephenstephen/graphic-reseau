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
        'jquery',
        'Lyranetwork_Sogecommerce/js/view/payment/method-renderer/sogecommerce-abstract'
    ],
    function($, Component) {
        'use strict';

        return Component.extend({
            defaults: {
                template: 'Lyranetwork_Sogecommerce/payment/sogecommerce-oney',
                sogecommerceOneyOption: window.checkoutConfig.payment.sogecommerce_oney.availableOptions ?
                    window.checkoutConfig.payment.sogecommerce_oney.availableOptions[0]['key'] : null
            },

            initObservable: function() {
                this._super().observe('sogecommerceOneyOption');
                return this;
            },

            getData: function() {
                var data = this._super();
                data['additional_data']['sogecommerce_oney_option'] = this.sogecommerceOneyOption();

                return data;
            },

            showLabel: function() {
                return true;
            },

            getAvailableOptions: function() {
                return window.checkoutConfig.payment.sogecommerce_oney.availableOptions;
            },

            getErrorMessage: function() {
                return $.cookie('sogecommerce_oney_error');
            },

            isPlaceOrderActionAllowed: function() {
                if ($.cookie('sogecommerce_oney_error')) {
                    return false;
                }

                return this._super();
            }
        });
    }
);
