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
        'Lyranetwork_Systempay/js/view/payment/method-renderer/systempay-abstract'
    ],
    function (Component) {
        'use strict';

        return Component.extend({
            defaults: {
                template: 'Lyranetwork_Systempay/payment/systempay-choozeo',
                systempayChoozeoOption: window.checkoutConfig.payment.systempay_choozeo.availableOptions ?
                    window.checkoutConfig.payment.systempay_choozeo.availableOptions[0]['key'] : null
            },

            initObservable: function () {
                this._super().observe('systempayChoozeoOption');
                return this;
            },

            getData: function () {
                var data = this._super();
                data['additional_data']['systempay_choozeo_option'] = this.systempayChoozeoOption();

                return data;
            },

            showLabel: function () {
                return true;
            },

            getAvailableOptions: function () {
                return window.checkoutConfig.payment.systempay_choozeo.availableOptions;
            }
        });
    }
);
