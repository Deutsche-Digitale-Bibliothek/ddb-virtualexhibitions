<?php $title = __('Edit Page Content: "%s"', metadata('exhibit_page', 'title', array('no_escape' => true))); ?>
<?php echo head(array('title'=> html_escape($title), 'bodyclass'=>'exhibits')); ?>
<?php echo flash(); ?>
<?php $wysiwygSelector = 'textarea:not(.notwysiwygable)'; ?>
<script>
(function($) {
    $.fn.ginaZoomSelector = function() {
        var button = $(this);
        button.on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var element = $(this);
            var zoomHelperContainer = $('<div id="zoomhelper-container" class="zoomhelper-container">' +
                    '<div id="zoom-helper" class="zoom-helper">' +
                        '<h2>Zoomausschnitt wählen</h2>' +
                        '<p class="explanation">Ziehen Sie das Rechteck an den Ecken auf den gewünschten Ausschnitt.</p>' +
                        '<div id="zoom-helper-image" class="zoom-helper-image"></div>' +
                    '</div>' +
                '</div>'
            );
            var close = $('<button id="close-zoom-helper" class="close-zoom-helper">OK</button>');
            close.appendTo($('#zoom-helper', zoomHelperContainer));
            var closeNoVal = $('<button id="close-zoom-helper-noval" class="close-zoom-helper red button">Keinen Auschnitt setzen</button>');
            closeNoVal.appendTo($('#zoom-helper', zoomHelperContainer));

            $('<img src="' + element.data('zoom-image') + '">').on('load', function() {
                var image = $(this);
                var imageContainer = $('#zoom-helper-image', zoomHelperContainer);
                imageContainer.append(image);
                zoomHelperContainer.appendTo('body');
                var $canvas = $('<canvas id="zoom-helper-canvas" class="zoom-helper-canvas" width="' +
                    image.width() + '" height="' + image.height() + '"></canvas>');
                imageContainer.append($canvas);
                var canvasOffset = $canvas.offset(),
                    ctx = $canvas[0].getContext('2d'),
                    rect = {},
                    drag = false,
                    mouseX,
                    mouseY,
                    closeEnough = 20,
                    dragTL = dragBL = dragTR = dragBR = false;

                    var inputField = $('#' + element.data('zoom-helperfor'));
                    var currentValue = inputField.val();
                    if (currentValue === '') {
                        var rect = {
                            startX: $canvas.width() / 4,
                            startY: $canvas.height() / 4,
                            w: $canvas.width() / 2,
                            h: $canvas.height() / 2
                        };
                    } else {
                        currentValueJs = JSON.parse(currentValue);
                        console.log(currentValueJs);
                        var rect = {
                            startX: $canvas.width() / 100 * currentValueJs.startX,
                            startY: $canvas.height() / 100 * currentValueJs.startY,
                            w: $canvas.width() / 100 * currentValueJs.w,
                            h: $canvas.height() / 100 * currentValueJs.h
                        };
                    }

                    close.on('click', function() {
                        if (rect.startX < 0) {
                            rect.startX = 0;
                        }
                        if (rect.startY < 0) {
                            rect.startY = 0;
                        }
                        inputField.val('{' +
                            '"startX": ' + Math.round((rect.startX / $canvas.width() * 100) * 100) / 100 + ', ' +
                            '"startY": ' + Math.round((rect.startY / $canvas.height() * 100) * 100) / 100 + ', ' +
                            '"w": ' + Math.round((rect.w / $canvas.width() * 100) * 100) / 100 + ', ' +
                            '"h": ' + Math.round((rect.h / $canvas.height() * 100) * 100) / 100 +
                            '}'
                        );
                        zoomHelperContainer.remove();
                    });

                    closeNoVal.on('click', function() {
                        if (rect.startX < 0) {
                            rect.startX = 0;
                        }
                        if (rect.startY < 0) {
                            rect.startY = 0;
                        }
                        inputField.val('');
                        zoomHelperContainer.remove();
                    });

                    function init() {
                        $canvas.on('mousedown', handleMouseDown);
                        zoomHelperContainer.on('mouseup', handleMouseUp);
                        $canvas.on('mousemove', handleMouseMove);
                        draw();
                    }

                    function handleMouseDown(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        mouseY = e.pageY - canvasOffset.top;
                        mouseX = e.pageX - canvasOffset.left;
                        // 1. top left
                        if (checkCloseEnough(mouseX, rect.startX) && checkCloseEnough(mouseY, rect.startY)) {
                            dragTL = true;
                        }
                        // 2. top right
                        else if (checkCloseEnough(mouseX, rect.startX + rect.w) && checkCloseEnough(mouseY, rect.startY)) {
                            dragTR = true;

                        }
                        // 3. bottom left
                        else if (checkCloseEnough(mouseX, rect.startX) && checkCloseEnough(mouseY, rect.startY + rect.h)) {
                            dragBL = true;

                        }
                        // 4. bottom right
                        else if (checkCloseEnough(mouseX, rect.startX + rect.w) && checkCloseEnough(mouseY, rect.startY + rect.h)) {
                            dragBR = true;

                        }
                        fixNegative();
                        ctx.clearRect(0, 0, $canvas.width(), $canvas.height());
                        draw();
                    }

                    function checkCloseEnough(p1, p2) {
                        return Math.abs(p1 - p2) < closeEnough;
                    }

                    function handleMouseUp() {
                        dragTL = dragTR = dragBL = dragBR = false;
                    }

                    function handleMouseMove(e) {
                        mouseY = e.pageY - canvasOffset.top;
                        mouseX = e.pageX - canvasOffset.left;
                        if (dragTL) {
                            rect.w += rect.startX - mouseX;
                            rect.h += rect.startY - mouseY;
                            rect.startX = mouseX;
                            rect.startY = mouseY;
                        } else if (dragTR) {
                            rect.w = Math.abs(rect.startX - mouseX);
                            rect.h += rect.startY - mouseY;
                            rect.startY = mouseY;
                        } else if (dragBL) {
                            rect.w += rect.startX - mouseX;
                            rect.h = Math.abs(rect.startY - mouseY);
                            rect.startX = mouseX;
                        } else if (dragBR) {
                            rect.w = Math.abs(rect.startX - mouseX);
                            rect.h = Math.abs(rect.startY - mouseY);
                        }
                        fixNegative();
                        ctx.clearRect(0, 0, $canvas.width(), $canvas.height());
                        draw();
                    }

                    function fixNegative() {
                        if (rect.w < 0) {
                            rect.startX = rect.startX - (rect.w * 2);
                            rect.w = rect.w * -1;
                        }
                        if (rect.h < 0) {
                            rect.startY = rect.startY - (rect.h * 2);
                            rect.h = rect.h * -1;
                        }
                    }

                    function draw() {
                        ctx.fillStyle = "rgba(0, 0, 0, 0.8)";
                        ctx.fillRect(rect.startX, rect.startY, rect.w, rect.h);
                        drawHandles();
                    }

                    function drawCircle(x, y, radius, i) {
                        var startAngle= i*Math.PI/2;
                        var endAngle= startAngle+Math.PI/2;
                        ctx.fillStyle = "#FF0000";
                        ctx.beginPath();
                        ctx.moveTo(x,y);
                        ctx.arc(x, y, radius, startAngle, endAngle);
                        ctx.closePath();
                        ctx.fill();
                    }

                    function drawHandles() {
                        var circleSize = 20
                        drawCircle(rect.startX, rect.startY, circleSize, 0);
                        drawCircle(rect.startX + rect.w, rect.startY, circleSize, 1);
                        drawCircle(rect.startX + rect.w, rect.startY + rect.h, circleSize, 2);
                        drawCircle(rect.startX, rect.startY + rect.h, circleSize, 3);
                    }

                    init();
            });
        })
        return this;
    };
}(jQuery));
</script>
<div id="exhibits-breadcrumb">
    <a href="<?php echo html_escape(url('exhibits/edit/' . $exhibit['id']));?>"><?php echo html_escape($exhibit['title']); ?></a>  &gt;
    <?php echo html_escape($title); ?>
</div>
<form id="page-form" method="post" action="<?php echo html_escape(url(array(
    'module'=>'exhibit-builder',
    'controller'=>'exhibits',
    'action'=>'edit-page-content',
    'id' => metadata('exhibit_page', 'id')))); ?>">

    <?php echo get_view()->formHidden('slug', $exhibit_page->slug); ?>
    <div class="seven columns alpha">
        <div id="page-metadata-list">
            <h2><?php echo __('Page Layout'); ?></h2>
            <div id="layout-metadata">
            <?php
                $layout = metadata('exhibit_page', 'layout', array('no_escape' => true));
                $imgFile = web_path_to(EXHIBIT_LAYOUTS_DIR_NAME ."/$layout/layout.gif");
                echo '<img src="'. html_escape($imgFile) .'" alt="' . html_escape($layout) . '"/>';
            ?>
                <strong><?php echo __($layoutName); ?></strong>
                <p><?php echo __($layoutDescription); ?></p>
            </div>
            <button id="page_metadata_form" name="page_metadata_form" type="submit"><?php echo __('Edit Page'); ?></button>
        </div>
        <?php
        $optionsForm = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'page-options-form-' . $exhibit_page->layout . '.php';
        if (is_file($optionsForm)) {
            require $optionsForm;
        }
        ?>
        <div id="layout-all">
            <h2><?php echo __('Page Content'); ?></h2>
            <div id="layout-form">
                <?php exhibit_builder_render_layout_form($layout); ?>
            </div>
        </div>
    </div>
    <div class="three columns omega">
        <div id="save" class="panel">
            <?php echo $this->formSubmit('continue', __('Save Changes'), array('class'=>'submit big green button')); ?>
            <?php echo $this->formSubmit('page_form', __('Save and Add Another Page'), array('class'=>'submit big green button')); ?>
            <?php if ($exhibit_page->exists()): ?>
                <?php echo exhibit_builder_link_to_exhibit($exhibit, __('View Public Page'), array('class' => 'big blue button', 'target' => '_blank'), $exhibit_page); ?>
            <?php endif; ?>
        </div>
    </div>
</form>
<?php //This item-select div must be outside the <form> tag for this page, b/c IE7 can't handle nested form tags. ?>
<div id="search-items" style="display:none;">
    <div id="item-select"></div>
</div>
<script type="text/javascript" charset="utf-8">
//<![CDATA[
    jQuery(document).ready(function() {
        var exhibitBuilder = new Omeka.ExhibitBuilder();
        // Set the exhibit item uri
        exhibitBuilder.itemContainerUri = <?php echo js_escape(url('exhibits/item-container')); ?>;
        // Set the paginated exhibit items uri
        exhibitBuilder.paginatedItemsUri = <?php echo js_escape(url('exhibit-builder/items/browse')); ?>;
        exhibitBuilder.removeItemText = <?php echo js_escape(__('Remove This Item')); ?>;
        // Get the paginated items
        exhibitBuilder.getItems();
        jQuery(document).bind('omeka:loaditems', function() {
            // Hide the page search form
            jQuery('#page-search-form').hide();
            jQuery('#show-or-hide-search').click( function(){
                var searchForm = jQuery('#page-search-form');
                if (searchForm.is(':visible')) {
                    searchForm.hide();
                } else {
                    searchForm.show();
                }

                var showHideLink = jQuery(this);
                showHideLink.toggleClass('show-form');
                if (showHideLink.hasClass('show-form')) {
                    showHideLink.text(<?php echo js_escape(__('Show Search Form')); ?>);
                } else {
                    showHideLink.text(<?php echo js_escape(__('Hide Search Form')); ?>);
                }
                return false;
            });
        });
        // Search Items Dialog Box
         jQuery('#search-items').dialog({
             autoOpen: false,
             width: Math.min(jQuery(window).width() - 100, 820),
             height: Math.min(jQuery(window).height() - 50, 500),
             title: <?php echo js_escape(__('Attach an Item')); ?>,
             modal: true,
             buttons: {
                <?php echo js_escape(__('Attach Selected Item')); ?>: function() {
                    exhibitBuilder.attachSelectedItem();
                     jQuery(this).dialog('close');
                 }
             },
             open: function() { jQuery('body').css('overflow', 'hidden'); },
             beforeClose: function() { jQuery('body').css('overflow', 'inherit'); }
         });
    });
    // Omeka.wysiwyg();
    Omeka.ExhibitBuilder.wysiwygSelector('<?php echo $wysiwygSelector; ?>');
    jQuery(window).load(function() {
        Omeka.ExhibitBuilder.addNumbers();
    });
    jQuery(document).bind('exhibitbuilder:attachitem', function (event) {
        // Add tinyMCE to all textareas in the div where the item was attached.
        jQuery(event.target).find('<?php echo $wysiwygSelector; ?>').each(function () {
            // We should remove tinyMCE in exhibit.js at removeItemLink.bind() line 173 ...
            tinyMCE.execCommand('mceRemoveEditor', false, this.id);
            tinyMCE.execCommand('mceAddEditor', false, this.id);
            // console.log(this.id);
        });
    });
//]]>
</script>
<?php echo foot(); ?>
