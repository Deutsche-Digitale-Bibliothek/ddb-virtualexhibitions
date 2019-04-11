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
<form id="exhibit-metadata-form" method="post" class="exhibit-builder" enctype="multipart/form-data">
    <?php echo $this->formHidden('slug', $exhibit->slug); ?>
    <div class="seven columns alpha">
        <fieldset>
            <legend><?php echo __('Teamkachel'); ?></legend>
            <div class="field">
                <div class="two columns alpha">
                    <?php echo $this->formLabel('description', __('Beschreibung')); ?>
                </div>
                <div class="five columns omega inputs">
                    <?php echo $this->formTextarea('description', $storedTeam['description']); ?>
                </div>
            </div>
            <div class="field">
                <div class="two columns alpha">
                    <?php echo $this->formLabel('team_list', __('Team')); ?>
                </div>
                <div class="five columns omega inputs">
                    <?php echo $this->formTextarea('team_list', $storedTeam['team_list']); ?>
                </div>
            </div>
        </fieldset>
    </div>
    <div id="save" class="three columns omega panel">
        <?php echo $this->formSubmit('save_exhibit', __('Save Changes'), array('class'=>'submit big green button')); ?>
    </div>
</form>
<script type="text/javascript" charset="utf-8">
    jQuery(window).load(function() {
        Omeka.ExhibitBuilder.wysiwyg();
    });
</script>
<?php echo foot(); ?>
