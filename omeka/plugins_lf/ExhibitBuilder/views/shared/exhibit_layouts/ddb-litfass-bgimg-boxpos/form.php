<fieldset class="<?php echo html_escape($layout); ?>">
    <div class="section">
        <h4><?php echo __('Hintergrundbild'); ?></h4>
        <div class="hide-caption">
            <?php echo ExhibitDdbHelper::exhibit_builder_layout_form_item(1, true); ?>
        </div>
        <h4><?php echo __('Textblock'); ?></h4>
        <?php echo exhibit_builder_layout_form_text(1); ?>
    </div>
</fieldset>