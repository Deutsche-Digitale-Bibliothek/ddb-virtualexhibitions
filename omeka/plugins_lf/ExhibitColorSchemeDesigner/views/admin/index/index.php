<?php
$head = array(
    'bodyclass' => 'exhibit-color-scheme-designer primary',
    'title' => html_escape(__('Farbschema Gestalter für Ausstellungen')),
    'content_class' => 'horizontal-nav'
);
echo head($head);
echo flash();
?>
<h2>Hier können Sie die Farbpalette der Ausstellung anpassen.</h2>
<form id="exhibit-color-scheme-designer-form" method="post" enctype="multipart/form-data">
    <?php echo $this->formHidden('exhibit-color-scheme-designer_colorpalette', $colorpalette); ?>
    <div id="colorRepeaters">
    <?php foreach ($colors as $colorKey => $color): ?>
        <div class="panel panel-default palette_color_panel">
            <div class="panel-body gina-form clearfix">
                <button class="btn red" style="display:block; float: right;" id="palette_color_delete_<?php echo $colorKey; ?>"
                    style="" title="Farbe löschen" data-delnum="<?php echo $colorKey; ?>">
                    <i class="fa fa-times" aria-hidden="true"></i>
                </button>
                <div class="field">
                    <div class="two columns alpha">
                        <label for="palette_color_<?php echo $colorKey; ?>">Name der Farbe</label>
                    </div>
                    <div class="five columns omega inputs">
                        <p class="explanation">
                            Der Name muss innerhalb der Palette einzigartig sein.
                            Erlaubt sind Kleinbuchstaben a-z (ohne Umlaute), Zahlen sowie "_" und "-"
                        </p>
                        <input type="text" class="form-control palette_color" id="palette_color_<?php echo $colorKey; ?>"
                            name="palette[<?php echo $colorKey; ?>][color]" value="<?php echo $color->color; ?>">
                    </div>
                </div>
                <div class="field">
                    <div class="two columns alpha">
                        <label for="palette_hex_<?php echo $colorKey; ?>">Farbwert</label>
                    </div>
                    <div class="five columns omega inputs">
                        <input type="color" value="<?php echo $color->hex; ?>" class="form-control" id="palette_hex_<?php echo $colorKey; ?>"
                        name="palette[<?php echo $colorKey; ?>][hex]">
                        <div class="palette_hex_show"><?php echo $color->hex; ?></div>
                    </div>
                </div>
                <div class="field">
                    <div class="two columns alpha">
                        <label for="palette_type_<?php echo $colorKey; ?>">Typ der Farbe</label>
                    </div>
                    <div class="five columns omega inputs">
                        <p class="explanation">
                            &quot;hell&quot;: Helle Hintergrundfarbe mit dunkler Schrift.<br>
                            &quot;dunkel&quot;: Dunkle Hintergrundfarbe mit heller Schrift.
                        </p>
                        <select class="form-control" id="palette_type_<?php echo $colorKey; ?>" name="palette[<?php echo $colorKey; ?>][type]">
                            <option value="light" <?php if ($color['type'] == 'light'): echo 'selected'; endif; ?>>hell</option>
                            <option value="dark" <?php if ($color['type'] == 'dark'): echo 'selected'; endif; ?>>dunkel</option>
                        </select>
                    </div>
                </div>
                <div class="field">
                    <div class="two columns alpha">
                        <label for="palette_menu_<?php echo $colorKey; ?>">Typ der Farbe</label>
                    </div>
                    <div class="five columns omega inputs">
                        <div class="radio">
                            <label>
                                <input type="radio" name="palette_menu" id="palette_menu_<?php echo $colorKey; ?>" value="<?php echo $colorKey; ?>"
                                <?php if ($color['menu'] == '1') { echo 'checked'; }; ?>>
                                Diese Farbe als Farbe für aktive Felder im Navigationsmenü verwenden.
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    </div>
    <div class="panel">
        <button id="addColor" class="button blue" style="display: block; width: 100%;">
            <i class="fa fa-plus" aria-hidden="true"></i>
            Farbe hinzufügen
        </button>
    </div>
    <div id="save" class="three columns omega">
        <?php echo $this->formSubmit('exhibit-color-scheme-designer_submit',
            __('Speichern'), array('class'=>'submit big grren button')); ?>
    </div>
</form>
<script>
(function ($) {

    'use strict';

    var colorCounter = <?php echo count($colors); ?>;

    function bindAddColor() {
        $('#addColor').bind('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $('#colorRepeaters').append(
                '<div class="panel panel-default palette_color_panel">' +
                    '<div class="panel-body gina-form clearfix">' +
                        '<button class="btn red" style="display:block; float: right;" id="palette_color_delete_' + colorCounter + '" ' +
                            'style="margin:-31px -16px 0 0;" title="Farbe löschen" data-delnum="' + colorCounter + '">' +
                            '<i class="fa fa-times" aria-hidden="true"></i> ' +
                        '</button>' +
                        '<div class="field">' +
                            '<div class="two columns alpha">' +
                                '<label for="palette_color_' + colorCounter + '">Name der Farbe</label>' +
                            '</div>' +
                            '<div class="five columns omega inputs">' +
                                '<p class="explanation">' +
                                    'Der Name muss innerhalb der Palette einzigartig sein. ' +
                                    'Erlaubt sind Kleinbuchstaben a-z (ohne Umlaute), Zahlen sowie "_" und "-"' +
                                '</p>' +
                                '<input type="text" class="form-control palette_color" id="palette_color_' + colorCounter + '" ' +
                                    'name="palette[' + colorCounter + '][color]">' +
                            '</div>' +
                        '</div>' +
                        '<div class="field">' +
                            '<div class="two columns alpha">' +
                                '<label for="palette_hex_' + colorCounter + '">Farbwert</label>' +
                            '</div>' +
                            '<div class="five columns omega inputs">' +
                                '<input type="color" value="#666666" class="form-control" id="palette_hex_' + colorCounter + '" ' +
                                'name="palette[' + colorCounter + '][hex]">' +
                                '<div class="palette_hex_show">#666666</div>' +
                            '</div>' +
                        '</div>' +
                        '<div class="field">' +
                            '<div class="two columns alpha">' +
                                '<label for="palette_type_' + colorCounter + '">Typ der Farbe</label>' +
                            '</div>' +
                            '<div class="five columns omega inputs">' +
                                '<p class="explanation">' +
                                    '&quot;hell&quot;: Helle Hintergrundfarbe mit dunkler Schrift.<br>' +
                                    '&quot;dunkel&quot;: Dunkle Hintergrundfarbe mit heller Schrift.' +
                                '</p>' +
                                '<select class="form-control" id="palette_type_' + colorCounter + '" name="palette[' + colorCounter + '][type]">' +
                                    '<option value="light">hell</option>' +
                                    '<option value="dark">dunkel</option>' +
                                '</select>' +
                            '</div>' +
                        '</div>' +
                        '<div class="field">' +
                            '<div class="two columns alpha">' +
                                '<label for="palette_menu_' + colorCounter + '">Typ der Farbe</label>' +
                            '</div>' +
                            '<div class="five columns omega inputs">' +
                                '<div class="radio">' +
                                    '<label>' +
                                        '<input type="radio" name="palette_menu" id="palette_menu_' + colorCounter +
                                            '" value="' + colorCounter + '">' +
                                        'Diese Farbe als Farbe für aktive Felder im Navigationsmenü verwenden.' +
                                    '</label>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                '</div>'
            );
            $('#palette_color_delete_' + colorCounter).on('click', deleteColor);
            $('#palette_color_' + colorCounter).on('change', checkUniqueColor);
            $('#palette_hex_' + colorCounter).on('change', setHex);
            colorCounter++;
        });
    }

    function deleteColor(e)
    {
        e.preventDefault();
        $(this).parents('.palette_color_panel').remove();
    }

    function bindDeleteColor() {
        for (var i = 0; i < colorCounter; i++) {
            $('#palette_color_delete_' + i).on('click', deleteColor);
        }
    }

    function bindUniqueColor() {
        for (var i = 0; i < colorCounter; i++) {
            $('#palette_color_' + i).on('change', checkUniqueColor);
        }
    }

    function checkUniqueColor() {
        var currentVal = $(this).val();
        var count = 0;
        $('.palette_color').each(function(index) {
            if (currentVal !== '' && $(this).val() === currentVal) {
                count++;
            }
        });
        if (count > 1) {
            alert('Farbnamen sind nicht einzigartig');
        }
    }

    function bindHex() {
        for (var i = 0; i < colorCounter; i++) {
            $('#palette_hex_' + i).on('change', setHex);
        }
    }

    function setHex() {
        $(this).siblings('.palette_hex_show').text($(this).val());
    }

    function main() {
        bindAddColor();
        bindDeleteColor();
        bindUniqueColor();
        bindHex();
    }

    $(function() {
        main();
    });

})(jQuery);
</script>
<?php echo foot(); ?>
