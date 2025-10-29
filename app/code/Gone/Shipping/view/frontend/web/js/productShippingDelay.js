/*
 * Copyright © 410 Gone (contact@410-gone.fr). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

define([
    "jquery",
    "jquery/ui",
    "domReady!"
], function ($) {
    "use strict";

    function main(config, element) {
        let $element = $(element);
        let ajaxUrl = config.ajaxUrl;
        let productId = config.productId;

        $.ajax({
            url: ajaxUrl,
            type: "POST",
            data: {
                productId: productId,
            },
        }).done(function (data) {
            $element.html(data.output);
            return true;
        });
    }
    return main;
});
