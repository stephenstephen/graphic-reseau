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
                template: 'Lyranetwork_Sogecommerce/payment/sogecommerce-choozeo',
                sogecommerceChoozeoOption: window.checkoutConfig.payment.sogecommerce_choozeo.availableOptions ?
                    window.checkoutConfig.payment.sogecommerce_choozeo.availableOptions[0]['key'] : null
            },

            initObservable: function() {
                this._super().observe('sogecommerceChoozeoOption');
                return this;
            },

            getData: function() {
                var data = this._super();
                data['additional_data']['sogecommerce_choozeo_option'] = this.sogecommerceChoozeoOption();

                return data;
            },

            showLabel: function() {
                return true;
            },

            getAvailableOptions: function() {
                return window.checkoutConfig.payment.sogecommerce_choozeo.availableOptions;
            }
        });
    }
);
