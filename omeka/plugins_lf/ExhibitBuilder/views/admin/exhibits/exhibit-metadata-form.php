<form id="exhibit-metadata-form" method="post" class="exhibit-builder" enctype="multipart/form-data">
    <?php echo $this->formHidden('slug', $exhibit->slug); ?>
    <div class="seven columns alpha">
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
                <?php echo $this->formLabel('institutions', __('Teilhabende Institutionen')); ?>
            </div>
            <div class="five columns omega inputs">
                <p class="explanation"><?php echo __('Liste der teilhabenden Institutionen'); ?></p>
                <?php echo $this->formTextarea('institutions', $exhibit->institutions, array('rows'=>'8','cols'=>'40')); ?>
            </div>
        </div>
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
                    <div class="example-color-box" style="background-color:<?php echo $color['hex']; ?>;color:<?php echo ($color['type'] === 'dark')? '#fff' : '#1d1d1b'; ?>;">
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
                <?php if (!empty($exhibit->titlebackground) && is_file(FILES_DIR . '/layout/titlebackground/' . $exhibit->titlebackground)): ?>
                <a href="<?php echo WEB_FILES . '/layout/titlebackground/' . $exhibit->titlebackground; ?>" target="_blank"><img src="<?php echo WEB_FILES . '/layout/titlebackground/' . $exhibit->titlebackground; ?>" style="display:block; height:80px; margin-bottom:10px;"></a>
                <?php endif; ?>
                <?php echo $this->formFile('titlebackground'); ?>
            </div>
        </div>
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
    </div>
    <div id="save" class="three columns omega panel">
        <?php echo $this->formSubmit('save_exhibit', __('Save Changes'), array('class'=>'submit big green button')); ?>
        <?php $currentuser = Zend_Registry::get('bootstrap')->getResource('currentuser'); ?>
        <?php if($currentuser->role === 'super'): ?>
        <div id="public-featured">
            <div class="public">
                <label for="public"><?php echo __('Public'); ?>:</label>
                <?php echo $this->formCheckbox('public', $exhibit->public, array(), array('1', '0')); ?>
            </div>
        </div>
    <?php endif; ?>
    </div>
</form>
<script type="text/javascript" charset="utf-8">
jQuery(window).load(function() {
    Omeka.ExhibitBuilder.wysiwyg();
});
</script>
