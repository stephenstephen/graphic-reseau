/**
 * Copyright © Lyra Network.
 * This file is part of Systempay plugin for Magento 2. See COPYING.md for license details.
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
        'Lyranetwork_Systempay/js/view/payment/method-renderer/systempay-abstract'
    ],
    function ($, Component) {
        'use strict';

        return Component.extend({
            defaults: {
                template: 'Lyranetwork_Systempay/payment/systempay-oney',
                systempayOneyOption: window.checkoutConfig.payment.systempay_oney.availableOptions ?
                    window.checkoutConfig.payment.systempay_oney.availableOptions[0]['key'] : null
            },

            initObservable: function () {
                this._super().observe('systempayOneyOption');
                return this;
            },

            getData: function () {
                var data = this._super();
                data['additional_data']['systempay_oney_option'] = this.systempayOneyOption();

                return data;
            },

            showLabel: function () {
                return true;
            },

            getAvailableOptions: function () {
                return window.checkoutConfig.payment.systempay_oney.availableOptions;
            },

            getErrorMessage: function () {
                return $.cookie('systempay_oney_error');
            },

            isPlaceOrderActionAllowed: function () {
                if ($.cookie('systempay_oney_error')) {
                    return false;
                }

                return this._super();
            }
        });
    }
);
