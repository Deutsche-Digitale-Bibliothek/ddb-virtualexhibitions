;(function($, window, document, undefined) {

    "use strict";

    var pluginName = "imgmapinfo",
        defaults = {}
    ;

    function Plugin (element, options) {
        this.element = element;
        this.settings = $.extend({}, defaults, options);
        this._defaults = defaults;
        this._name = pluginName;
        this.msg = null;

        this.init();
    }

    $.extend(Plugin.prototype, {

        init: function() {
            this.msg = $('<div></div>')
                .attr({
                    id: 'imgmapinfo',
                    class: 'imgmapinfo'
                });
            $(this.element).parent().append(this.msg);
            this.bindArea();
        },

        bindArea: function() {
            var msg = this.msg;
            $('area', this.element).on('mouseenter', function (event) {
                msg.html($(this).data('imgmap')).show();
            });

            $('area', this.element).on('mouseleave', function (event) {
                msg.hide().html('');
            });
        },
    });

    $.fn[pluginName] = function(options) {
        return this.each(function() {
            if (!$.data(this, "plugin_" + pluginName )) {
                $.data(this, "plugin_" + pluginName, new Plugin(this, options));
            }
        });
    };

})(jQuery, window, document);