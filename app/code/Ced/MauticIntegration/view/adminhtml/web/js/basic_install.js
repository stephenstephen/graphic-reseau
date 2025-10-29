/**
 * CedCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End User License Agreement (EULA)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://cedcommerce.com/license-agreement.txt
 *
 * @category    Ced
 * @package     Ced_MauticIntegration
 * @author        CedCommerce Core Team <connect@cedcommerce.com>
 * @copyright   Copyright CedCommerce (http://cedcommerce.com/)
 * @license     http://cedcommerce.com/license-agreement.txt
 */
define([
    'jquery',
    'prototype'
], function (jQuery) {
    return function (config) {
        jQuery(document).ready(function () {
            jQuery('#basic_install').on('click', function () {
                basicInstall();
            });

            function basicInstall() {
                params = {};
                new Ajax.Request(config.BasicInstallAjaxUrl, {
                    asynchronous: true,
                    parameters: {},
                    showLoader: true,
                    onSuccess: function (transport) {
                        var response = JSON.parse(transport.responseText);
                        if (response && response.status) {
                            jQuery('.ced-wish-loading').remove();
                            left = (jQuery(window).width() / 2) - (550 / 2);
                            top = (jQuery(window).height() / 2) - (550 / 2);
                            window.open(response.base_url + 'mauticintegration/config/getcode', 'popup', "width=400, height=450, top=" + top + ", left=" + left);
                        } else {
                            jQuery('.ced-wish-loading').remove();
                            jQuery('#basic_install').after('<p class="ced-wish-loading fa fa-spinner" style="color:red;margin-left:2px">' + response.message + '</p>');
                        }
                    }
                });
            }
        });
    }
});
