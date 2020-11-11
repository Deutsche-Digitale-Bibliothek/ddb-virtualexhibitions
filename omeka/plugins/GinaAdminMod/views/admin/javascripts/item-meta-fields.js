(function($) {
    $.fn.addRequiredToItemMeta = function() {
        $(this).submit(function(e){
            var valid = true;
            $('.explanation', $(this)).each(function() {
                if ($(this).text().indexOf("Pflichtfeld") >= 0) {
                    var validCurrent = true;
                    var formElement = $('.input-block textarea', $(this).parent());
                    if (formElement.length !== 0) {
                        if ($.trim(formElement.val()).length === 0) {
                            valid = false;
                            validCurrent = false;
                        }
                    } else {
                        var formElement = $('.input-block select', $(this).parent());
                        if(formElement.val().length === 0) {
                            valid = false;
                            validCurrent = false;
                        }
                    }
                    if (validCurrent !== true) {
                        $(this).addClass('item-meta-field-required');
                    } else {
                        $(this).removeClass('item-meta-field-required');
                    }
                }
            });
            if (valid !== true) {
                e.preventDefault();
                alert('Bitte füllen Sie bei den Metadaten des Objekts die Pflichtfelder aus!');
            }
            var obligatoryCopyright = [
                '[[license:G-RR-AF]]',
                '[[license:G-RR-AA]]',
                '[[license:CC-BY-3.0-DEU]]',
                '[[license:CC-BY-4.0-INT]]',
                '[[license:CC-BY-SA-3.0-DEU]]',
                '[[license:CC-BY-SA-4.0-INT]]',
                '[[license:CC-BY-ND-3.0-DEU]]',
                '[[license:CC-BY-ND-4.0-INT]]',
                '[[license:CC-BY-NC-3.0-DEU]]',
                '[[license:CC-BY-NC-4.0-INT]]',
                '[[license:CC-BY-NC-SA-3.0-DEU]]',
                '[[license:CC-BY-NC-SA-4.0-INT]]',
                '[[license:CC-BY-NC-ND-3.0-DEU]]',
                '[[license:CC-BY-NC-ND-4.0-INT]]'
            ];
            var needsCopyright = false;
            var hasCopyright = false;
            $.each($('#item-form').serializeArray(), function(idx, val) {
                if (val.name === 'Elements[72][0][text]' && $.inArray(val.value, obligatoryCopyright) > -1) {
                    needsCopyright = true;
                }
                if (val.name === 'Elements[86][0][text]' && (val.value && val.value.length > 0)) {
                    hasCopyright = true;
                }
            });
            if (needsCopyright && !hasCopyright) {
                e.preventDefault();
                $('#element-86 .explanation').addClass('item-meta-field-required');
                alert('Bitte füllen Sie bei den Metadaten des Objekts das Feld "Copyright" aus!');
            } else {
                $('#element-86 .explanation').removeClass('item-meta-field-required');
            }
        });
    };
}(jQuery));

jQuery(document).ready(function($) {
    $('#item-form').addRequiredToItemMeta();
});