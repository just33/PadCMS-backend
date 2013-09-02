/**
 * Copyright (c) PadCMS (http://www.padcms.net)
 *
 * Licensed under the CeCILL-C license
 * http://www.cecill.info/licences/Licence_CeCILL-C_V1-en.html
 * http://www.cecill.info/licences/Licence_CeCILL-C_V1-fr.html
 */
var colorPickerHandler = {
    init: function (collection) {
      $(collection).each(function(){
        colorPickerHandler.initItem(this);
      });
    },
    initItem: function (colorPickerBlock) {
            var colorPickerInput = $('input', colorPickerBlock);
            var colorSelector    = $('div.color-selector', colorPickerBlock);

            colorPickerInput.ColorPicker({
                color: '#d9411a',
                onSubmit: function(hsb, hex, rgb, el) {
                        $(el).val(hex);
                        $(el).ColorPickerHide();
                },
                onBeforeShow: function () {
                        $(this).ColorPickerSetColor(this.value);
                },
                onChange: function (hsb, hex, rgb) {
                  $('div', colorSelector).css('backgroundColor', '#' + hex);
                }
            })
            .bind('keyup', function(){
		                $(this).ColorPickerSetColor(this.value);
            });

            colorSelector.ColorPicker({
                    color: '#d9411a',
                    onSubmit: function(hsb, hex, rgb, el) {
                            $(colorPickerInput).val(hex);
                            $(el).ColorPickerHide();
                    },
                    onBeforeShow: function () {
                            if(colorPickerInput.attr('value') != '') {
                                $(this).ColorPickerSetColor('#' + colorPickerInput.attr('value'));
                            }
                    },
                    onChange: function (hsb, hex, rgb) {
                            $('div', colorSelector).css('backgroundColor', '#' + hex);
                    }
            });

            $('.colorpicker').css('z-index', "100000000");
    }
};