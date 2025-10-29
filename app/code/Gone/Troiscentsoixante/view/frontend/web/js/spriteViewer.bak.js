define([
    'jquery',
    'Magento_Ui/js/modal/modal',
    'sprite.spin'
], function ($, modal) {

    "use strict";

    $.widget('gone.spriteViewer', {

        options: {
            spriteContainerClass: '',
            source: '',
            width: '',
            height: '',
            frames: '',
            framesX: '',
            framesY: '',
            responsive: true,
            modalContainerId: '',
            modalLinkClass: '',
            type: 'popup',
            innerScroll: true,
            title: $.mage.__('360 Viewer')
        },

        _init: function () {
           this._assignControls()._listen();
        },

        _openModal: function () {
           modal(this.options, $(this.options.modalContainerId));
           $(this.options.modalContainerId).modal('openModal');
            this._loadSprite()
        },

        _loadSprite: function () {
            const sprite = $(this.options.spriteContainerClass);
            const spriteOptions= {
                detectSubsampling: false,
                wrap:true,
                source: this.options.source,
                width: this.options.width,
                height: this.options.height,
                frames: this.options.frames,
                framesX : this.options.framesX,
                framesY : this.options.framesY,
                responsive: false,
                sense: -1
            }
            sprite.spritespin(spriteOptions);
        },

        _assignControls: function () {
            this.controls = {
                openModal: $(this.options.modalLinkClass),
            };
            return this;
        },

        _listen: function () {
            this._on(this.controls.openModal, {
                'click': this._openModal
            });
        }
    });
    return $.gone.spriteViewer;
})
