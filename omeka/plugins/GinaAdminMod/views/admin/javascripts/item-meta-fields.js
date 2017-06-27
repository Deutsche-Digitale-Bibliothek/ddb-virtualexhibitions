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
        });
    };
}(jQuery));

jQuery(document).ready(function($) {
    $('#item-form').addRequiredToItemMeta();
});