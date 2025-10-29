/*global define*/
define(['jquery', 'jquery/ui', 'mage/translate'], function ($) {
    'use strict';

    return {
        options:{
            file:null,
            runUrl:null,
            result:null,
            running:false,
        },

        init: function (url, result) {
            this.options.runUrl = url;
            this.result = $(result);
        },

        file: function (file, object) {
            if (!this.options.running) {
                this.options.file = file;
                $('.import-file').removeClass('active');
                $(object).addClass('active');
                this.setResultStatus($.mage.__('Waiting...'), 'waiting');
            }
        },

        run: function () {
            var price = this;

            if (price.options.file && price.options.runUrl) {
                price.setResultStatus($.mage.__('Processing...'), 'processing');
                price.disabledLaunch(true);
                $.ajax({
                    url: price.options.runUrl,
                    type: 'post',
                    context: this,
                    data: {'file':price.options.file,'mode':$('#colissimo-price-mode').val()},
                    success: function (response) {
                        price.setResultStatus(response.message, response.status);
                        $('#colissimo-price-file-container').find('.active').remove();
                    },
                    complete: function () {
                        price.disabledLaunch(false);
                    }
                });
            } else {
                price.setResultStatus($.mage.__('Please select a file to import'), 'error');
            }
        },

        disabledLaunch: function (enable) {
            var uploader = $('.colissimo-price-uploader');
            uploader.find('button').prop("disabled", enable);
            uploader.find('input').prop("disabled", enable);
            uploader.find('select').prop("disabled", enable);
            this.options.running = enable;
        },

        setResultStatus: function (text, className) {
            var result = this.result;

            result.removeClass('waiting');
            result.removeClass('processing');
            result.removeClass('error');
            result.removeClass('success');

            result.addClass(className);
            result.text(text);
        }
    }
});