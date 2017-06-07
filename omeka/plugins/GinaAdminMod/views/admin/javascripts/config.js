if (typeof Omeka === 'undefined') {
    Omeka = {};
}

(function ($) {
    $(window).load(function () {
        Omeka.wysiwyg({
            mode: "specific_textareas",
            editor_selector: "html-input",
            forced_root_block: ""
        });
    });
})(jQuery);
