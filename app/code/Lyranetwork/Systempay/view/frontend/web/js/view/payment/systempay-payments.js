/**
 * Copyright © Lyra Network.
 * This file is part of Systempay plugin for Magento 2. See COPYING.md for license details.
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
    function (Component, rendererList) {
        'use strict';
        rendererList.push(
            {
                type: 'systempay_standard',
                component: 'Lyranetwork_Systempay/js/view/payment/method-renderer/systempay-standard'
            },
            {
                type: 'systempay_multi',
                component: 'Lyranetwork_Systempay/js/view/payment/method-renderer/systempay-multi'
            },
            {
                type: 'systempay_gift',
                component: 'Lyranetwork_Systempay/js/view/payment/method-renderer/systempay-gift'
            },
            {
                type: 'systempay_choozeo',
                component: 'Lyranetwork_Systempay/js/view/payment/method-renderer/systempay-choozeo'
            },
            {
                type: 'systempay_oney',
                component: 'Lyranetwork_Systempay/js/view/payment/method-renderer/systempay-oney'
            },
            {
                type: 'systempay_fullcb',
                component: 'Lyranetwork_Systempay/js/view/payment/method-renderer/systempay-fullcb'
            },
            {
                type: 'systempay_sepa',
                component: 'Lyranetwork_Systempay/js/view/payment/method-renderer/systempay-sepa'
            },
            {
                type: 'systempay_paypal',
                component: 'Lyranetwork_Systempay/js/view/payment/method-renderer/systempay-paypal'
            },
            {
                type: 'systempay_franfinance',
                component: 'Lyranetwork_Systempay/js/view/payment/method-renderer/systempay-franfinance'
            },
            {
                type: 'systempay_other',
                component: 'Lyranetwork_Systempay/js/view/payment/method-renderer/systempay-other'
            }
        );

        /** Add view logic here if needed */
        return Component.extend({});
    }
);
