define([
    'jquery'
], function ($) {
    'use strict';

    var body = $('body');

    body.on('change', '#etiquette_retour_adresse', function () {
        $('#etiquette_retour_adresse_value').val($(this).val());
    });

    body.on('mousedown', 'a.etiquette_retour_link', function (e) {
        e.preventDefault();
        e.stopPropagation();

        let value = $('#etiquette_retour_adresse_value').val();

        var currentUrl = $(this).attr('href');
        var url = new URL(currentUrl);
        url.searchParams.set("recipient_address_type", value);
        $(this).attr('href', url.href);

        return false;
    });
});
