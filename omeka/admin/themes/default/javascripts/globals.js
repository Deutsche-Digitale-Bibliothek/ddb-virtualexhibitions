if (!Omeka) {
    var Omeka = {};
}

(function ($) {

    Omeka.wysiwyg_content_css = function () {
        if (ddbExhibitType === 'leporello') {
            return '../../../themes/ddb/css/bundle.css';
        } else {
            return '../../../themes/ddb/css/spa.min.css';
        }
    };

    /**
     * Add the TinyMCE WYSIWYG editor to a page.
     * Default is to add to all textareas.
     *
     * @param {Object} [params] Parameters to pass to TinyMCE, these override the
     * defaults.
     */
    Omeka.wysiwyg = function (params) {
        // Default parameters
        var styleFormats = [
            { title: 'blockquote', block: 'blockquote' },
            { title: 'cite', inline: 'cite' },
            { title: 'Überschrift 2', block: 'h2' },
            { title: 'Überschrift 3', block: 'h3' },
            { title: 'Überschrift 4', block: 'h4' },
            { title: 'Überschrift 5', block: 'h5' },
            { title: 'Absatz', block: 'p' },
            { title: 'Große Schrift', block: 'p', classes: 'typo_xxl' },
            { title: 'kleine Schrift', block: 'p', classes: 'typo_xxs' }
            // { title: 'Red header', block: 'h1', classes: 'example1', styles: { color: '#ff0000', border: '1px solid #ff3300' } },
        ];
        if (ddbExhibitType === 'litfass_ddb') {
            styleFormats.push({title: 'DDB Überschrift 1', block: 'h1', classes: 'litfass_ddb' });
            styleFormats.push({title: 'DDB Überschrift 2', block: 'h2', classes: 'litfass_ddb' });
            styleFormats.push({title: 'DDB Überschrift 3', block: 'h3', classes: 'litfass_ddb' });
        }
        initParams = {
            convert_urls: false,
            selector: "textarea",
            // menubar: false,
            menubar: 'edit view help',
            statusbar: true,
            branding: false,
            toolbar_items_size: "small",
            // toolbar: "blockquote formatselect ",
            toolbar: "bold italic underline removeformat | alignleft aligncenter alignright alignjustify | bullist numlist | link unlink styleselect | code fullscreen",
            plugins: "lists,link,code,paste,media,autoresize,fullscreen,help",
            style_formats: styleFormats,
            content_css : Omeka.wysiwyg_content_css(),
            autoresize_max_height: 500,
            entities: "160,nbsp,173,shy,8194,ensp,8195,emsp,8201,thinsp,8204,zwnj,8205,zwj,8206,lrm,8207,rlm",
            verify_html: false,
            add_unload_trigger: false,
            language: 'de',
            forced_root_block: '',
            force_p_newlines: true,
            valid_classes: {
                '*': '',
                'hp': 'typo_xxl typo_xxs',
                'h1': 'litfass_ddb',
                'h2': 'litfass_ddb',
                'h3': 'litfass_ddb',
            }
        };

        tinymce.init($.extend(initParams, params));
    };

    Omeka.deleteConfirm = function () {
        $('.delete-confirm').click(function (event) {
            var url;

            event.preventDefault();
            if ($(this).is('input')) {
                url = $(this).parents('form').attr('action');
            } else if ($(this).is('a')) {
                url = $(this).attr('href');
            } else {
                return;
            }

            $.post(url, function (response){
                $(response).dialog({modal:true});
            });
        });
    };

    Omeka.saveScroll = function () {
        var $save   = $("#save"),
            $window = $(window),
            offset  = $save.offset(),
            topPadding = 62,
            $contentDiv = $("#content");
        if (document.getElementById("save")) {
            $window.scroll(function () {
                if($window.scrollTop() > offset.top && $window.width() > 767 && ($window.height() - topPadding - 85) >  $save.height()) {
                    $save.stop().animate({
                        marginTop: $window.scrollTop() - offset.top + topPadding
                        });
                } else {
                    $save.stop().animate({
                        marginTop: 0
                    });
                }
            });
        }
    };

    Omeka.stickyNav = function() {
        var $nav    = $("#content-nav"),
            $window = $(window);
        if ($window.height() - 50 < $nav.height()) {
            $nav.addClass("unfix");
        }
        $window.resize( function() {
            if ($window.height() - 50 < $nav.height()) {
                $nav.addClass("unfix");
            } else {
                $nav.removeClass("unfix");
            }
        });
    };


    Omeka.showAdvancedForm = function () {
        var advancedForm = $('#advanced-form');
        $('#search-form').addClass("with-advanced");
        $('#search-form button').addClass("blue button");
        advancedForm.before('<a href="#" id="advanced-search" class="blue button">Advanced Search</a>');
        advancedForm.click(function (event) {
            event.stopPropagation();
        });
        $("#advanced-search").click(function (event) {
            event.preventDefault();
            event.stopPropagation();
            advancedForm.fadeToggle();
            $(document).click(function (event) {
                if (event.target.id == 'query') {
                    return;
                }
                advancedForm.fadeOut();
                $(this).unbind(event);
            });
        });
    };

    Omeka.skipNav = function () {
        $("#skipnav").click(function() {
            $("#content").attr("tabindex", -1).focus();
        });

        $("#content").on("blur focusout", function () {
            $(this).removeAttr("tabindex");
        });
    };

    Omeka.addReadyCallback = function (callback, params) {
        this.readyCallbacks.push([callback, params]);
    };

    Omeka.runReadyCallbacks = function () {
        for (var i = 0; i < this.readyCallbacks.length; ++i) {
            var params = this.readyCallbacks[i][1] || [];
            this.readyCallbacks[i][0].apply(this, params);
        }
    };

    Omeka.mediaFallback = function () {
        $('.omeka-media').on('error', function () {
            if (this.networkState === HTMLMediaElement.NETWORK_NO_SOURCE ||
                this.networkState === HTMLMediaElement.NETWORK_EMPTY
            ) {
                $(this).replaceWith(this.innerHTML);
            }
        });
    };

    Omeka.warnIfUnsaved = function() {
        var deleteConfirmed = false;
        var setSubmittedFlag = function () {
            $(this).data('omekaFormSubmitted', true);
        };

        var setOriginalData = function () {
            $(this).data('omekaFormOriginalData', $(this).serialize());
        };

        var formsToCheck = $('form[method=POST]:not(.disable-unsaved-warning)');
        formsToCheck.on('o:form-loaded', setOriginalData);
        formsToCheck.each(function () {
            var form = $(this);
            form.trigger('o:form-loaded');
            form.submit(setSubmittedFlag);
        });

        $('body').on('submit', 'form.delete-confirm-form', function () {
            deleteConfirmed = true;
        });

        $(window).on('beforeunload', function() {
            var preventNav = false;
            formsToCheck.each(function () {
                var form = $(this);
                var originalData = form.data('omekaFormOriginalData');
                var hasFile = false;
                if (form.data('omekaFormSubmitted') || deleteConfirmed) {
                    return;
                }

                form.trigger('o:before-form-unload');

                if (window.tinyMCE) {
                    tinyMCE.triggerSave();
                }

                form.find('input[type=file]').each(function () {
                    if (this.files.length) {
                        hasFile = true;
                        return false;
                    }
                });

                if (form.data('omekaFormDirty')
                    || (originalData && originalData !== form.serialize())
                    || hasFile
                ) {
                    preventNav = true;
                    return false;
                }
            });

            if (preventNav) {
                return 'You have unsaved changes.';
            }
        });
    };

    Omeka.readyCallbacks = [
        [Omeka.deleteConfirm, null],
        [Omeka.saveScroll, null],
        [Omeka.stickyNav, null],
        [Omeka.showAdvancedForm, null],
        [Omeka.skipNav, null],
        [Omeka.mediaFallback, null],
        [Omeka.warnIfUnsaved, null]
    ];
})(jQuery);
