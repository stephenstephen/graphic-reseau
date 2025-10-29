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
define(['jquery', 'jquery/ui', 'accordion'], function ($) {
        return function (config) {
            $(".batch-container").accordion({collapsible: true, active: false});

            var totalRecords = parseInt(config.Total);
            var countOfSuccess = 0;
            var id = 0;
            var liFinished = document.getElementById('liFinished');
            var updateStatus = document.getElementById('updateStatus');
            var updateRow = document.getElementById('updateRow');
            var statusImage = document.getElementById('statusImage');
            var successImg = config.SuccessImg;
            var errorImg = config.ErrorImg;

            //call on load
            sendRequest();

            function sendRequest() {
                updateStatus.innerHTML = (id + 1) + ' Of ' + totalRecords + ' Processing';
                var request = $.ajax({
                    type: "GET",
                    url: config.AjaxUrl,
                    data: {batchid: id},
                    success: function (data) {
                        var json = data;
                        id++;
                        if (json.hasOwnProperty('success')) {
                            countOfSuccess++;
                            var span = document.createElement('li');
                            span.innerHTML =
                                '<img src="' + successImg + '"><span>' +
                                json.success + '</span>';
                            span.id = 'id-' + id;
                            updateRow.parentNode.insertBefore(span, updateRow);
                        } else {
                            var span = document.createElement('li');
                            if (json.hasOwnProperty('error')) {
                                if (json.hasOwnProperty('messages')) {
                                    errorMessage = parseErrors(json.messages);
                                } else if (json.hasOwnProperty('exceptionmessages')) {
                                    errorMessage = json.exceptionmessages;
                                }
                                var heading = '<span>' +
                                    '<img src="' + errorImg + '"> ' + json.error + '.</span>';

                                var errorTemplate = '<div class="batch-container">' +
                                    '<div data-role="collapsible" style="cursor: pointer;">' +
                                    '<div data-role="trigger">' + heading + '</div></div>' +
                                    '<div data-role="content">' + errorMessage.errors + '</div></div>';
                            }
                            span.innerHTML = errorTemplate;
                            span.id = 'id-' + id;
                            updateRow.parentNode.insertBefore(span, updateRow);
                            $(".batch-container").accordion({collapsible: true, active: false});
                        }
                    },

                    error: function () {
                        id++;
                        var span = document.createElement('li');
                        span.innerHTML = '<img src="' + errorImg + '"><span>Something went wrong </span>';
                        span.id = 'id-' + id;
                        span.style = 'background-color:#FDD';
                        updateRow.parentNode.insertBefore(span, updateRow);
                    },

                    complete: function () {
                        if (id < totalRecords) {
                            sendRequest();
                        } else {
                            statusImage.src = successImg;
                            var span = document.createElement('li');
                            span.innerHTML =
                                '<img src="' + successImg + '">' +
                                '<span id="updateStatus">' +
                                totalRecords + ' Customer batch(s) processed.' + '</span>';
                            liFinished.parentNode.insertBefore(span, liFinished);
                            document.getElementById("liFinished").style.display = "block";
                            updateStatus.innerHTML = (id) + ' of ' + totalRecords + ' Processed.';
                        }
                    },
                    dataType: "json"
                });
            }

            function parseErrors(errors) {
                var data = (errors);
                var result = {
                    'status': true,
                    'errors': ''
                };

                if (data) {
                    result.errors = '<table class="data-grid" style="margin-bottom:10px; margin-top:10px"><tr>' +
                        '<th style="padding:15px">Sl. No.</th>' +
                        '<th style="padding:15px">Email</th>' +
                        '<th style="padding:15px">Errors</th></tr>';
                    var products = Object.keys(data).length;
                    var counter = 0;
                    $.each(data, function (index, value) {
                        var messages = '';
                        var outerMessages = '';
                        outerMessages += '<ul style="list-style: none;">';
                        $.each(value.errors, function (i, v) {
                            if (typeof v === 'object' && v !== null && Object.keys(v).length > 0) {
                                $.each(v, function (attribute, err) {
                                    messages += '<li><b>' + attribute + '</b> : ' + err + '</li>';
                                });
                            } else {
                                messages += '<li><b>' + i + '</b> : ' + v + '</li>';
                            }
                        });
                        if (messages === '') {
                            counter++;
                            outerMessages += '<li><b style="color:forestgreen;">No errors.</b></li>';
                        } else {
                            outerMessages += messages;
                        }
                        outerMessages += '</ul>';

                        // if (!value['Field']) {
                        //     value['Field'] = value['SellerSku'];
                        // }
                        result.errors += '<tr><td>' + (value['id']) + '</td><td>' + (value['email']) + '</td><td>' + (outerMessages) +
                            '</td></tr>';
                    });
                    result.errors += '</table>';
                    if (products === counter) {
                        result.status = false;
                    }
                }
                return result;
            }
        }
    }
);


