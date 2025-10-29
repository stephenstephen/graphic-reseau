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
        'Lyranetwork_Sogecommerce/js/view/payment/method-renderer/sogecommerce-abstract'
    ],
    function(Component) {
        'use strict';

        return Component.extend({
            defaults: {
                template: 'Lyranetwork_Sogecommerce/payment/sogecommerce-multi',
                sogecommerceMultiOption: window.checkoutConfig.payment.sogecommerce_multi.availableOptions ?
                    window.checkoutConfig.payment.sogecommerce_multi.availableOptions[0]['key'] : null,
                sogecommerceCcType: window.checkoutConfig.payment.sogecommerce_multi.availableCcTypes ?
                    window.checkoutConfig.payment.sogecommerce_multi.availableCcTypes[0]['value'] : null
            },

            initObservable: function() {
                this._super();
                this.observe('sogecommerceCcType');
                this.observe('sogecommerceMultiOption');

                return this;
            },

            getData: function() {
                var data = this._super();

                if (this.getEntryMode() == 2) { // Payment means selection on merchant site.
                    data['additional_data']['sogecommerce_multi_cc_type'] = this.sogecommerceCcType();
                }

                data['additional_data']['sogecommerce_multi_option'] = this.sogecommerceMultiOption();

                return data;
            },

            showLabel: function() {
                return true;
            },

            getAvailableOptions: function() {
                return window.checkoutConfig.payment.sogecommerce_multi.availableOptions;
            }
        });
    }
);
