<?php
$currentuser = Zend_Registry::get('bootstrap')->getResource('currentuser');
$selectableExhibitTypes = array(
    'litfass' => 'Litfaß Partner Standard (Single Page Ausstellung)',
    'litfass_featured' => 'Litfaß Partner Featured (Single Page Ausstellung)'
);
$allExhibitTypes = array(
    'leporello' => 'Leporello (klassische Ausstellung)',
    'litfass' => 'Litfaß Partner Standard (Single Page Ausstellung)',
    'litfass_featured' => 'Litfaß Partner Featured (Single Page Ausstellung)',
    'litfass_ddb' => 'Litfaß DDB Exhibition (Single Page Ausstellung)'
);
$navColors = array(
    'dark' => 'dunkel',
    'light' => 'hell'
);
if ($exhibit->exhibit_type === 'litfass_ddb' && (!isset($exhibit->nav_color) || empty($exhibit->nav_color))) {
    $exhibit->nav_color = 'dark';
}
?>
<form id="exhibit-metadata-form" method="post" class="exhibit-builder" enctype="multipart/form-data">
    <?php echo $this->formHidden('slug', $exhibit->slug); ?>
    <div class="seven columns alpha">
    <?php if($currentuser->role === 'super' && array_key_exists($exhibit->exhibit_type, $selectableExhibitTypes)): ?>
        <fieldset style="margin-bottom: 16px;">
            <legend><?php echo __('Typ der Ausstellung'); ?></legend>
            <div class="field">
                <?php echo $this->formSelect('exhibit_type', $exhibit->exhibit_type, array(), $selectableExhibitTypes); ?>
            </div>
        </fieldset>
    <?php else: ?>
        <fieldset style="margin-bottom: 16px;">
            <legend><?php echo __('Typ der Ausstellung'); ?></legend>
            <div class="field"><?php
                echo (isset($exhibit->exhibit_type) && !empty($exhibit->exhibit_type))?
                $allExhibitTypes[$exhibit->exhibit_type] : ''; ?></div>
        </fieldset>
    <?php endif; ?>
        <fieldset>
            <legend><?php echo __('Exhibit Metadata'); ?></legend>
            <div class="field">
                <div class="two columns alpha">
                    <?php echo $this->formLabel('title', __('Title')); ?>
                </div>
                <div class="five columns omega inputs">
                    <?php echo $this->formText('title', $exhibit->title); ?>
                </div>
            </div>
            <div class="field">
                <div class="two columns alpha">
                    <?php echo $this->formLabel('subtitle', __('Untertitel')); ?>
                </div>
                <div class="five columns omega inputs">
                    <?php echo $this->formText('subtitle', $exhibit->subtitle); ?>
                </div>
            </div>
            <div class="field">
                <div class="two columns alpha">
                    <?php echo $this->formLabel('shorttitle', __('Kurztitel')); ?>
                </div>
                <div class="five columns omega inputs">
                    <?php echo $this->formText('shorttitle', $exhibit->shorttitle); ?>
                </div>
            </div>
            <?php if ($exhibit->exhibit_type === 'litfass_ddb'): ?>
            <div class="field">
                <div class="two columns alpha">
                    <?php echo $this->formLabel('nav_color', __('Farbe der Navigation')); ?>
                </div>
                <div class="five columns omega inputs">
                    <?php echo $this->formSelect('nav_color', $exhibit->nav_color, array(), $navColors); ?>
                </div>
            </div>
            <?php endif; ?>
        </fieldset>
        <fieldset>
            <legend><?php echo __('Startkachel'); ?></legend>
            <div class="field">
                <div class="two columns alpha">
                    <?php echo $this->formLabel('titlebackgroundcolor', __('Hintergrundfarbe Startkachel')); ?>
                </div>
                <div class="five columns omega inputs">
                    <?php
                        $colorpalette = metadata('exhibit', 'colorpalette');
                        $colors = ExhibitDdbHelper::getColorsFromExhibitColorPalette($colorpalette);
                        $values = ExhibitDdbHelper::getColornamesFromExhibitColorPalette($colorpalette);
                    ?>
                    <p class="explanation">
                        <?php echo __('Hintergrundfarbe der Startkachel'); ?>
                    </p>
                    <div class="clearfix example-color-box-container">
                    <?php foreach ($colors as $color): ?>
                        <div class="example-color-box"
                            data-name="<?php echo $color['color']; ?>"
                            style="background-color:<?php echo $color['hex']; ?>;color:<?php echo ($color['type'] === 'dark')? '#fff' : '#1d1d1b'; ?>;">
                            <?php echo $color['color']; ?>
                        </div>
                    <?php endforeach; ?>
                    </div>
                    <?php echo $this->formSelect('titlebackgroundcolor', $exhibit->titlebackgroundcolor, array(), $values); ?>
                </div>
            </div>

            <div class="field">
                <div class="two columns alpha">
                    <?php echo $this->formLabel('titlebackground', __('Hintergrundbild Startkachel')); ?>
                </div>
                <div class="five columns omega inputs">
                    <p class="explanation"><?php echo __('Falls gewünscht, hier ein Hintergrundbild für die Startkachel hochalden'); ?></p>
                    <?php
                        $hasTitlebackground = false;
                        if (!empty($exhibit->titlebackground) && is_file(FILES_DIR . '/layout/titlebackground/' . $exhibit->titlebackground)):
                        $hasTitlebackground = true;
                    ?>
                    <a href="<?php echo WEB_FILES . '/layout/titlebackground/' . $exhibit->titlebackground; ?>" target="_blank"><img src="<?php echo WEB_FILES . '/layout/titlebackground/' . $exhibit->titlebackground; ?>" class="img-sm"></a>
                    <?php endif; ?>
                    <?php echo $this->formFile('titlebackground'); ?>
                    <?php if ($hasTitlebackground): ?>
                    <div class="mt-10">
                        <?php echo $this->formCheckbox('deleteTitlebackground', 1); ?>
                        <?php echo $this->formLabel('deleteTitlebackground', __('Hintergrundbild entfernen'),
                            array('class' => 'deleteCheckbox')); ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="field">
                <div class="two columns alpha">
                    <?php echo $this->formLabel('titlebgpos', __('Position des Hintergrundbildes')); ?>
                </div>
                <div class="five columns omega inputs">
                <?php
                    echo $this->formSelect(
                        'titlebgpos',                // name
                        $exhibit->titlebgpos,        // value
                        null,                        // attribs
                        array(
                            'left top' => 'oben links',
                            'center top' => 'oben zentriert',
                            'right top' => 'oben rechts',
                            'left center' => 'mitte links',
                            'center center' => 'mitte zentriert',
                            'right center' => 'mitte rechts',
                            'left bottom' => 'unten links',
                            'center bottom' => 'unten zentriert',
                            'right bottom' => 'unten rechts'
                        ),                           // options
                        "<br />\n"                   // listsep
                    );
                ?>
                </div>
            </div>

            <?php if ($exhibit->exhibit_type === 'litfass_ddb'): ?>
            <div class="field">
                <div class="two columns alpha">
                    <?php echo $this->formLabel('titleimage', __('Titelbild Startkachel')); ?>
                </div>
                <div class="five columns omega inputs">
                    <p class="explanation"><?php echo __('Falls gewünscht, hier ein Titelbild im SVG-Format für die Startkachel hochalden'); ?></p>
                    <?php
                        $hasTitleimage = false;
                        if (!empty($exhibit->titleimage) && is_file(FILES_DIR . '/layout/titleimage/' . $exhibit->titleimage)):
                        $hasTitleimage = true;
                    ?>
                    <a href="<?php echo WEB_FILES . '/layout/titleimage/' . $exhibit->titleimage; ?>" target="_blank">
                        <img src="<?php echo WEB_FILES . '/layout/titleimage/' . $exhibit->titleimage; ?>" class="img-sm">
                    </a>
                    <?php endif; ?>
                    <?php echo $this->formFile('titleimage'); ?>
                    <?php if ($hasTitleimage): ?>
                    <div class="mt-10">
                        <?php echo $this->formCheckbox('deleteTitleimage', 1); ?>
                        <?php echo $this->formLabel('deleteTitleimage', __('Titelbild entfernen'),
                            array('class' => 'deleteCheckbox')); ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="field">
                <div class="two columns alpha">
                    <?php echo $this->formLabel('titlelogo', __('Titellogo Startkachel')); ?>
                </div>
                <div class="five columns omega inputs">
                    <p class="explanation"><?php echo __('Falls gewünscht, hier ein Titellogo (im SVG-Format) für die Startkachel hochalden'); ?></p>
                    <?php
                        $hasTitleLogo = false;
                        if (!empty($exhibit->titlelogo) && is_file(FILES_DIR . '/layout/titlelogo/' . $exhibit->titlelogo)):
                        $hasTitleLogo = true;
                    ?>
                    <a href="<?php echo WEB_FILES . '/layout/titlelogo/' . $exhibit->titlelogo; ?>" target="_blank">
                        <img src="<?php echo WEB_FILES . '/layout/titlelogo/' . $exhibit->titlelogo; ?>" class="img-sm">
                    </a>
                    <?php endif; ?>
                    <?php echo $this->formFile('titlelogo'); ?>
                    <?php if ($hasTitleLogo): ?>
                    <div class="mt-10">
                        <?php echo $this->formCheckbox('deleteTitlelogo', 1); ?>
                        <?php echo $this->formLabel('deleteTitlelogo', __('Titellogo entfernen'),
                            array('class' => 'deleteCheckbox')); ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <div class="field" id="gina_exhibit_metadata_theme_container">
                <div class="two columns alpha">
                    <?php echo $this->formLabel('theme', __('Theme')); ?>
                </div>
                <div class="five columns omega inputs">
                    <?php $values = array('' => __('Current Public Theme')) + exhibit_builder_get_themes(); ?>
                    <?php echo get_view()->formSelect('theme', $exhibit->theme, array(), $values); ?>
                    <?php if ($theme && $theme->hasConfig): ?>
                        <a href="<?php echo html_escape(url("exhibits/theme-config/$exhibit->id")); ?>" class="configure-button button"><?php echo __('Configure'); ?></a>
                    <?php endif;?>
                    <script>
                        jQuery(document).ready(function () {
                            jQuery('#theme').val('ddb').trigger('change');
                        });
                    </script>
                </div>
            </div>
        </fieldset>
        <?php
            $institutions = [[
                'name' => '',
                'url' => '',
                'logo' => '',
                'pos' => ''
            ]];
            if (!empty($exhibit->institutions)) {
                $institutions = ExhibitDdbHelper::getInstitutions($exhibit->institutions);
                // var_dump($institutions);
            }
            $institutionCounter = count($institutions);
        ?>
        <fieldset>
            <legend><?php echo __('Teilhabende Institutionen'); ?></legend>
            <div id="institutionRepeaters">
            <?php foreach ($institutions as $instKey => $institution): ?>
                <div class="repeaterfield clearfix">
                    <div class="field">
                        <div class="two columns alpha">
                            <?php echo $this->formLabel('institution[' . $instKey . '][name]', __('Name der Institution')); ?>
                        </div>
                        <div class="five columns omega inputs">
                            <p class="explanation"><?php echo __('Name der teilhabenden Institution'); ?></p>
                            <?php echo $this->formText('institution[' . $instKey . '][name]', $institution['name']); ?>
                        </div>
                    </div>
                    <div class="field">
                        <div class="two columns alpha">
                            <?php echo $this->formLabel('institution[' . $instKey . '][url]', __('URL der Institution')); ?>
                        </div>
                        <div class="five columns omega inputs">
                            <p class="explanation"><?php echo __('URL / Website der teilhabenden Institution'); ?></p>
                            <?php echo $this->formText('institution[' . $instKey . '][url]', $institution['url']); ?>
                        </div>
                    </div>
                    <div class="field">
                        <div class="two columns alpha">
                            <?php echo $this->formLabel('institution[' . $instKey . '][logo]', __('Logo der Institution')); ?>
                        </div>
                        <div class="five columns omega inputs">
                            <p class="explanation"><?php echo __('Logo der teilhabenden Institution hochalden'); ?></p>
                            <?php if (!empty($institution['logo']) && is_file(FILES_DIR . '/layout/institutionlogo/' . $institution['logo'])): ?>
                            <a href="<?php echo WEB_FILES . '/layout/institutionlogo/' . $institution['logo']; ?>" target="_blank">
                                <img src="<?php echo WEB_FILES . '/layout/institutionlogo/' . $institution['logo']; ?>" class="img-sm">
                            </a>
                            <?php endif; ?>
                            <?php echo $this->formFile('institution[' . $instKey . '][logo]'); ?>
                            <div class="mt-10">
                                <?php echo $this->formCheckbox('institution[' . $instKey . '][deletelogo]', 1); ?>
                                <?php echo $this->formLabel('institution[' . $instKey . '][deletelogo]', __('Logo entfernen'),
                                    array('class' => 'deleteCheckbox')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="field">
                        <div class="two columns alpha">
                            <?php echo $this->formLabel('institution[' . $instKey . '][pos]', __('Position der Institution')); ?>
                        </div>
                        <div class="five columns omega inputs">
                            <p class="explanation">
                                <?php echo __('Position der teilhabenden Institution in der Seitenanzeige. Hier kann eine Zahl eingegeben werden - je kleiner sie ist, desto weiter vorne steht die Institution.'); ?></p>
                            <?php echo str_replace('type="text"', 'type="number"', $this->formText('institution[' . $instKey . '][pos]', $institution['pos'])); ?>
                        </div>
                    </div>
                    <div class="field">
                        <div class="two columns alpha">
                            <?php echo $this->formLabel('institution[' . $instKey . '][delete]', __('Institution löschen')); ?>
                        </div>
                        <div class="five columns omega inputs">
                            <p class="explanation">
                                <?php echo __('Diese Institution komplett entfernen.'); ?>
                            </p>
                            <?php echo $this->formCheckbox('institution[' . $instKey . '][delete]', 1); ?>
                            <?php echo $this->formLabel('institution[' . $instKey . '][delete]', __('Institution löschen'),
                                    array('class' => 'deleteCheckbox')); ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
            <div>
                <button class="submit green button" id="addInstitution"><?php echo __('+ Institution hinzufügen'); ?></button>
            </div>
        </fieldset>
        <fieldset>
            <legend><?php echo __('Seitenkacheln'); ?></legend>
            <div id="pages-list-container">
                <?php if (!$exhibit->TopPages): ?>
                    <p><?php echo __('There are no pages.'); ?></p>
                <?php else: ?>
                    <p id="reorder-instructions"><?php echo __('To reorder pages, click and drag the page up or down to the preferred location.'); ?></p>
                    <?php echo common('page-list', array('exhibit' => $exhibit), 'exhibits'); ?>
                <?php endif; ?>
            </div>
            <div id="page-add">
                <input type="submit" name="add_page" id="add-page" value="<?php echo __('Add Page'); ?>" />
            </div>
        </fieldset>
        <fieldset>
            <legend><?php echo __('Apparatkacheln'); ?></legend>
            <ul class="apparatus-tiles">
                <li><div class="tile"><a href="<?php echo $this->url('exhibits/team/' . $exhibit->id); ?>"><?php echo __('Team'); ?></a></div></li>
                <li><div class="tile"><a href="<?php echo $this->url('exhibits/imprint/' . $exhibit->id); ?>"><?php echo __('Impressum'); ?></a></div></li>
            </ul>
        </fieldset>
    </div>
    <div id="save" class="three columns omega panel">
        <?php echo $this->formSubmit('save_exhibit', __('Save Changes'), array('class'=>'submit big green button')); ?>
        <?php if($currentuser->role === 'super'): ?>
        <div id="public-featured">
            <div class="public">
                <label for="public"><?php echo __('Public'); ?>:</label>
                <?php echo $this->formCheckbox('public', $exhibit->public, array(), array('1', '0')); ?>
            </div>
        </div>
        <?php endif; ?>
        <?php echo exhibit_builder_link_to_exhibit($exhibit, __('Ausstellung anzeigen'), array('class' => 'big blue button', 'target' => '_blank')); ?>
    </div>
</form>
<script type="text/javascript" charset="utf-8">
jQuery(document).ready(function($) {
    if (typeof window.Gina === 'undefined') {
        window.Gina = {};
    }
    window.Gina.institutionCounter = <?php echo $institutionCounter; ?>;
    window.Gina.institutionStrings = {
        name: '<?php echo htmlentities(__('Name der Institution'), ENT_QUOTES); ?>',
        nameExpl: '<?php echo htmlentities(__('Name der teilhabenden Institution'), ENT_QUOTES); ?>',
        url: '<?php echo htmlentities(__('URL der Institution'), ENT_QUOTES); ?>',
        urlExpl: '<?php echo htmlentities(__('URL / Website der teilhabenden Institution'), ENT_QUOTES); ?>',
        logo: '<?php echo htmlentities(__('Logo der Institution'), ENT_QUOTES); ?>',
        logoExpl: '<?php echo htmlentities(__('Logo der teilhabenden Institution hochalden'), ENT_QUOTES); ?>',
        logoDel: '<?php echo htmlentities(__('Logo entfernen'), ENT_QUOTES); ?>',
        pos: '<?php echo htmlentities(__('Position der Institution'), ENT_QUOTES); ?>',
        posExpl: '<?php echo htmlentities(__('Position der teilhabenden Institution in der Seitenanzeige. Hier kann eine Zahl eingegeben werden - je kleiner sie ist, desto weiter vorne steht die Institution.'), ENT_QUOTES); ?>'
    }
    $('#addInstitution').bind('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        if (window.Gina.institutionCounter > 5) {
            $(this).html('Maximale Anzahl erreicht');
            return;
        }
        $('#institutionRepeaters').append(
            '<div class="repeaterfield clearfix">' +
                '<div class="field">' +
                    '<div class="two columns alpha">' +
                        '<label for="institution-' + window.Gina.institutionCounter + '-name">' +
                            window.Gina.institutionStrings.name +
                        '</label>' +
                    '</div>' +
                    '<div class="five columns omega inputs">' +
                        '<p class="explanation">' + window.Gina.institutionStrings.nameExpl + '</p>' +
                        '<input type="text" name="institution[' +
                            window.Gina.institutionCounter + '][name]" id="institution-' +
                            window.Gina.institutionCounter + '-name" value="">' +
                    '</div>' +
                '</div>' +
                '<div class="field">' +
                    '<div class="two columns alpha">' +
                        '<label for="institution-' + window.Gina.institutionCounter + '-url">' +
                            window.Gina.institutionStrings.url +
                        '</label>' +
                    '</div>' +
                    '<div class="five columns omega inputs">' +
                        '<p class="explanation">' + window.Gina.institutionStrings.urlExpl + '</p>' +
                        '<input type="text" name="institution[' +
                            window.Gina.institutionCounter + '][url]" id="institution-' +
                            window.Gina.institutionCounter + '-url" value="">' +
                    '</div>' +
                '</div>' +
                '<div class="field">' +
                    '<div class="two columns alpha">' +
                        '<label for="institution-' + window.Gina.institutionCounter + '-logo">' +
                            window.Gina.institutionStrings.logo +
                        '</label>' +
                    '</div>' +
                    '<div class="five columns omega inputs">' +
                        '<p class="explanation">' + window.Gina.institutionStrings.logoExpl + '</p>' +
                        '<input type="file" name="institution[' +
                            window.Gina.institutionCounter + '][logo]" id="institution-' +
                            window.Gina.institutionCounter + '-logo">' +
                    '</div>' +
                '</div>' +
                '<div class="field">' +
                    '<div class="two columns alpha">' +
                        '<label for="institution-' + window.Gina.institutionCounter + '-pos">' +
                            window.Gina.institutionStrings.pos +
                        '</label>' +
                    '</div>' +
                    '<div class="five columns omega inputs">' +
                        '<p class="explanation">' + window.Gina.institutionStrings.posExpl + '</p>' +
                        '<input type="number" step="1" name="institution[' +
                            window.Gina.institutionCounter + '][pos]" id="institution-' +
                            window.Gina.institutionCounter + '-pos" value="">' +
                    '</div>' +
                '</div>' +
            '</div>'
        );
        window.Gina.institutionCounter++;
    });

    $('.example-color-box').on('click', function() {
        var name = $(this).data('name');
        $('#titlebackgroundcolor').val(name);
    })

    // slider
    $('#save_exhibit').on('click', function(e) {
        var sliderStart = 0;
        var nestingError = false;
        $('.page').each(function() {
            if ($(this).data('slider') === 'start') {
                sliderStart++;
            }
            if (sliderStart > 1) {
                nestingError = true;
            }
            if ($(this).data('slider') === 'end' && sliderStart > 0) {
                sliderStart--;
            }
        });
        if (sliderStart > 0) {
            e.preventDefault();
            e.stopPropagation();
            alert('<?php echo __('Falsche Sortierung des Sliders! Der Anfang des Sliders muss immmer vor dem Ende sein!'); ?>');
        }
        if (nestingError) {
            e.preventDefault();
            e.stopPropagation();
            alert('<?php echo __('Es ist nicht möglich Slider ineinander zu schachteln!'); ?>');
        }
    });
    $('#page-list .delete-toggle').on('click', function (e, data) {
        if (data !== 'automark') {
            var page = $(this).parents('.page');
            if (page.data('slider') === 'start') {
                var sliderEnd = page.nextAll('.slider-end').first();
                $('.delete-toggle', sliderEnd).trigger('click', 'automark');
            }
            if (page.data('slider') === 'end') {
                var sliderEnd = page.prevAll('.slider-start').first();
                $('.delete-toggle', sliderEnd).trigger('click', 'automark');
            }
        }
    });

});
jQuery(window).load(function() {
    Omeka.ExhibitBuilder.wysiwyg();
});
</script>