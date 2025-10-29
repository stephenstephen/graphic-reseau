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
                template: 'Lyranetwork_Systempay/payment/systempay-sepa',
                systempayUseIdentifier: 1,
            },

            initObservable: function () {
                this._super();
                this.observe('systempayUseIdentifier');

                return this;
            },

            getData: function () {
                var data = this._super();

                if (this.isOneClick()) {
                    data['additional_data']['systempay_sepa_use_identifier'] = this.systempayUseIdentifier();
                }

                return data;
            },

            isOneClick: function () {
                return window.checkoutConfig.payment[this.item.method].oneClick || false;
            },

            getMaskedPan: function () {
                return window.checkoutConfig.payment[this.item.method].maskedPan || null;
            },

            updatePaymentBlock: function (blockName) {
                $('.payment-method._active .payment-method-content .systempay-identifier li.systempay-sepa-block').hide();
                $('li.systempay-sepa-' + blockName + '-block').show();
            },
        });
    }
);
