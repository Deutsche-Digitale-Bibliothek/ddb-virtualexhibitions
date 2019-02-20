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
        this.focusLevel = 0;

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
            this.bindMsg();
            this.bindArea();
        },

        bindMsg: function() {
            var plugin = this;
            this.msg.on('mouseenter', function (event) {
                plugin.focusLevel = 2;
            });
            this.msg.on('mouseleave', function (event) {
                setTimeout(function () {
                    if (plugin.focusLevel === 2) {
                        plugin.msg.hide().html('');
                        plugin.focusLevel = 0;
                    } else {
                        plugin.focusLevel = 1;
                    }
                }, 80);
            });
        },

        bindArea: function() {
            var plugin = this;
            $('area', this.element).on('mouseenter', function (event) {
                if (plugin.focusLevel === 0) {
                    plugin.msg.html($(this).data('imgmap')).show();
                    plugin.focusLevel = 1;
                } else if (plugin.focusLevel === 2) {
                    plugin.focusLevel = 1;
                }
            });

            $('area', this.element).on('mouseleave', function (event) {
                setTimeout(function () {
                    if (plugin.focusLevel === 1) {
                        plugin.msg.hide().html('');
                        plugin.focusLevel = 0;
                    }
                }, 80);
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