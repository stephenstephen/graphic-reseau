/*global define*/
define([
    'jquery'
], function($) {
    'use strict';
    return function (options, element) {
        if(options.scriptfloatEnable == 1) {
            var scriptWidgetAv = "'"+options.scriptfloat+"'";
            scriptWidgetAv = scriptWidgetAv.replace(/&avClose;/gi, '>');
            scriptWidgetAv = scriptWidgetAv.replace(/&avOpen;/gi, '<');
            $('body').append(scriptWidgetAv);
        }
    };

});
