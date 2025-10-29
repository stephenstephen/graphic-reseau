/**
 * Copyright © Lyra Network.
 * This file is part of Sogecommerce plugin for Magento 2. See COPYING.md for license details.
 *
 * @author    Lyra Network (https://www.lyra.com/)
 * @copyright Lyra Network
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */

define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list',
        'Magento_Checkout/js/view/payment/default'
    ],
    function(Component, rendererList) {
        'use strict';
        rendererList.push(
            {
                type: 'sogecommerce_standard',
                component: 'Lyranetwork_Sogecommerce/js/view/payment/method-renderer/sogecommerce-standard'
            },
            {
                type: 'sogecommerce_multi',
                component: 'Lyranetwork_Sogecommerce/js/view/payment/method-renderer/sogecommerce-multi'
            },
            {
                type: 'sogecommerce_gift',
                component: 'Lyranetwork_Sogecommerce/js/view/payment/method-renderer/sogecommerce-gift'
            },
            {
                type: 'sogecommerce_choozeo',
                component: 'Lyranetwork_Sogecommerce/js/view/payment/method-renderer/sogecommerce-choozeo'
            },
            {
                type: 'sogecommerce_oney',
                component: 'Lyranetwork_Sogecommerce/js/view/payment/method-renderer/sogecommerce-oney'
            },
            {
                type: 'sogecommerce_fullcb',
                component: 'Lyranetwork_Sogecommerce/js/view/payment/method-renderer/sogecommerce-fullcb'
            },
            {
                type: 'sogecommerce_sepa',
                component: 'Lyranetwork_Sogecommerce/js/view/payment/method-renderer/sogecommerce-sepa'
            },
            {
                type: 'sogecommerce_paypal',
                component: 'Lyranetwork_Sogecommerce/js/view/payment/method-renderer/sogecommerce-paypal'
            },
            {
                type: 'sogecommerce_franfinance',
                component: 'Lyranetwork_Sogecommerce/js/view/payment/method-renderer/sogecommerce-franfinance'
            },
            {
                type: 'sogecommerce_other',
                component: 'Lyranetwork_Sogecommerce/js/view/payment/method-renderer/sogecommerce-other'
            }
        );

        /** Add view logic here if needed */
        return Component.extend({});
    }
);
