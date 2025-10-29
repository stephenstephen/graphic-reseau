define([
    'jquery',
    'Magento_Catalog/js/price-utils',
    'jquery/ui',
    'slider'
], function ($, priceUtils) {
    'use strict';

    $.widget('gone.fundingslider', {
        options: {
            sliderDiv: '#fundingslider',
            resultValueDiv: '#monthly_value',
            resultMonthDiv: '#months_value',
            resultTotalDiv: '#total_cost',
            sliderdata: '',
            price: '',
            group: 0,
            fundingData: {},
            sliderMinMax: [],
            sliderValues: [],
            moneyFormat:{
                decimalSymbol: ',',
                groupLength: 3,
                groupSymbol: " ",
                integerRequired: false,
                pattern: "%s €",
                precision: 2,
                requiredPrecision: 2
            }
        },

        _create: function () {
            this._super();
        },

        /**
         * @private
         */
        _init: function () {
            this._super();
            this._getTotalPrice();
            this._getCustomerGroup();
            this._getDataMapping();
            this._setSliderValues();
            this._setSliderMinMax();
            this._loadSlider();
        },

        _loadSlider: function () {
            var self = this
            new rSlider({
                target: this.options.sliderDiv,
                values: this.options.sliderValues,
                set: this.options.sliderMinMax,
                range: false,
                scale: true,
                labels: true,
                tooltip: false,
                onChange: function (val) {
                    self._getFundingValue(val, self)
                }
            })
        },

        _getDataMapping: function () {
            this.options.sliderdata = $(this.options.sliderDiv).data('mapping');
        },

        _getTotalPrice: function () {
            this.options.price = $(this.options.sliderDiv).data('price');
        },

        _getCustomerGroup: function () {
            this.options.group = $(this.options.sliderDiv).data('group');
        },

        _setSliderValues: function () {
            let rawData = []
            for (let duration in this.options.sliderdata) {
                if (duration.split('_')[0] === "duration") {
                    this.options.fundingData[duration.substring(9)] = this.options.sliderdata[duration]
                    rawData.push(duration.substring(9))
                }
            }
            this.options.sliderValues = rawData.sort().reverse()
        },

        _setSliderMinMax: function () {
            let data = this._setSliderValues
            this.options.sliderMinMax = [data[0], data[data.length]]
        },

        _getFundingValue: (val, self) => {

            let monthlyValue = self.options.price * (self.options.fundingData[val] / 100)
            monthlyValue = (self.options.group === 32 || self.options.group === 34) ? (monthlyValue / 1.2) : monthlyValue;
            let totalValue = monthlyValue * val

            $(self.options.resultMonthDiv).text(val)
            $(self.options.resultValueDiv).text(priceUtils.formatPrice(monthlyValue, self.options.moneyFormat))
            $(self.options.resultTotalDiv).text(priceUtils.formatPrice(totalValue, self.options.moneyFormat))
        }

    });

    return $.gone.fundingslider;
});
