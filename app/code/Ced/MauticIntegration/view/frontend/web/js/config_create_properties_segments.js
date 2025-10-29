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
            jQuery.ajax({
                url: config.PropertiesAjaxUrl,
                data: {form_key: window.FORM_KEY},
                type: 'POST'
            }).done(function (transport) {

                if (transport['errors']) {
                    $errorMessage = '<li class="error">' + transport['errors'] + '</li>';
                    console.log(transport);
                    jQuery('#mautic-responses').html($errorMessage);
                } else {

                    console.log(transport);
                    jQuery('#mautic-properties').html(transport[0]);
                    jQuery('#mautic-properties').removeClass('warning');
                    jQuery('#mautic-properties').addClass('success');
                    var appendHtml = '<li id="mautic-segments" class="warning">' + transport[1] + '</li>';
                    jQuery('#mautic-responses').append(appendHtml);

                    jQuery.ajax({
                        url: config.SegmentsAjaxUrl,
                        data: {form_key: window.FORM_KEY},
                        type: 'POST'
                    }).done(function (transport) {
                        if (transport['errors']) {
                            $errorMessage = '<li class="error">' + transport['errors'] + '</li>';
                            jQuery('#mautic-responses').html($errorMessage);
                        } else {
                            console.log(transport);
                            jQuery('#mautic-segments').html(transport[0]);
                            jQuery('#mautic-segments').removeClass('warning');
                            jQuery('#mautic-segments').addClass('success');
                            jQuery('#response-heading').html(transport[1]);
                            closeWindow();
                        }
                    });
                }
            });


            function closeWindow() {
                setTimeout(function () {
                    window.close();
                }, 3000);
            }
        });
    };
});