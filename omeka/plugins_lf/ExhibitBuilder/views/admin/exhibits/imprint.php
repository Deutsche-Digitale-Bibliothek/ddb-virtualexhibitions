<?php
queue_js_file(array('vendor/jquery.nestedSortable', 'navigation'));
$title = __('Edit Exhibit "%s"', $exhibit->title);
echo head(array('title' => html_escape($title), 'bodyclass' => 'exhibits'));
?>
<div id="exhibits-breadcrumb">
    <a href="<?php echo html_escape(url('exhibits/edit/' . $exhibit['id']));?>"><?php echo html_escape($exhibit['title']); ?></a>
</div>
<?php echo flash(); ?>
<?php
$theme = $exhibit->theme ? Theme::getTheme($exhibit->theme) : null;
?>
<form id="exhibit-metadata-form-imprint" method="post" class="exhibit-builder">
    <?php echo $this->formHidden('slug', $exhibit->slug); ?>
    <div class="seven columns alpha">
        <fieldset>
            <legend><?php echo __('Impressum'); ?></legend>
            <?php foreach($this->fields as $field): ?>
            <div class="field">
                <div class="two columns alpha">
                    <?php echo $this->formLabel($field['var'], $field['desc']); ?>
                </div>
                <div class="five columns omega inputs">
                    <?php if($this->storedImprint{$field['var']}): ?>
                    <?php echo $this->formTextarea($field['var'], $this->storedImprint{$field['var']}); ?>
                    <?php else: ?>
                    <?php echo $this->formTextarea($field['var'], ''); ?>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </fieldset>
    </div>
    <div id="save" class="three columns omega panel">
        <?php echo $this->formSubmit('save_exhibit_imprint', __('Save Changes'), array('class'=>'submit big green button')); ?>
    </div>
</form>
<script type="text/javascript" charset="utf-8">
    jQuery(window).load(function() {
        Omeka.ExhibitBuilder.wysiwyg();
    });
</script>
<?php echo foot(); ?>
