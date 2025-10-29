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
                template: 'Lyranetwork_Systempay/payment/systempay-multi',
                systempayMultiOption: window.checkoutConfig.payment.systempay_multi.availableOptions ?
                    window.checkoutConfig.payment.systempay_multi.availableOptions[0]['key'] : null,
                systempayCcType: window.checkoutConfig.payment.systempay_multi.availableCcTypes ?
                    window.checkoutConfig.payment.systempay_multi.availableCcTypes[0]['value'] : null
            },

            initObservable: function () {
                this._super();
                this.observe('systempayCcType');
                this.observe('systempayMultiOption');

                return this;
            },

            getData: function () {
                var data = this._super();

                if (this.getEntryMode() == 2) { // Payment means selection on merchant site.
                    data['additional_data']['systempay_multi_cc_type'] = this.systempayCcType();
                }

                data['additional_data']['systempay_multi_option'] = this.systempayMultiOption();

                return data;
            },

            showLabel: function () {
                return true;
            },

            getAvailableOptions: function () {
                return window.checkoutConfig.payment.systempay_multi.availableOptions;
            }
        });
    }
);
