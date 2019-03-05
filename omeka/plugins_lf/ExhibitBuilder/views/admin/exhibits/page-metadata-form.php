<?php
$title = ($actionName == 'Add') ? __('Add Page') : __('Edit Page "%s"', $exhibit_page->title);
echo head(array('title'=> $title, 'bodyclass'=>'exhibits'));
?>
<?php echo flash(); ?>
<form method="post" id="choose-layout" enctype="multipart/form-data">
    <?php echo $this->formHidden('slug', $exhibit_page->slug); ?>
    <div id="exhibits-breadcrumb">
        <a href="<?php echo html_escape(url('exhibits/edit/' . $exhibit['id']));?>"><?php echo html_escape($exhibit['title']); ?></a>  &gt;
        <?php echo html_escape($title); ?>
    </div>
    <div class="seven columns alpha">
    <fieldset>
        <legend><?php echo __('Page Metadata'); ?></legend>
        <div class="field">
            <div class="two columns alpha">
            <?php echo $this->formLabel('title', __('Title')); ?>
            </div>
            <div class="inputs five columns omega">
            <?php echo $this->formText('title', $exhibit_page->title); ?>
            </div>
        </div>
        <div class="field">
            <div class="two columns alpha">
            <?php echo $this->formLabel('backgroundcolor', __('Hintergrundfarbe')); ?>
            </div>
            <div class="inputs five columns omega">
            <?php
                $colorpalette = metadata('exhibit', 'colorpalette');
                $colors = ExhibitDdbHelper::getColorsFromExhibitColorPalette($colorpalette);
                $values = ExhibitDdbHelper::getColornamesFromExhibitColorPalette($colorpalette);
            ?>
            <p class="explanation"><?php echo __('Wählen Sie eine Hintergrundfarbe aus der Farbpalette.'); ?></p>
            <div class="clearfix example-color-box-container">
                <?php foreach ($colors as $color): ?>
                    <div class="example-color-box" style="background-color:<?php echo $color['hex']; ?>;color:<?php echo ($color['type'] === 'dark')? '#fff' : '#1d1d1b'; ?>;">
                        <?php echo $color['color']; ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php echo $this->formSelect('backgroundcolor', $exhibit_page->backgroundcolor, array(), $values); ?>
            </div>
        </div>
        <div class="field">
            <div class="two columns alpha">
                <?php echo $this->formLabel('pagethumbnail', __('Page Navigation Thumbnail')); ?>
            </div>
            <div class="five columns omega inputs">
                 <p class="explanation"><?php echo sprintf(
                     __('Minimum size for image (width x height) is %d x %d pixel'), 92, 71); ?></p>
                <?php if (!empty($exhibit_page->pagethumbnail) &&
                    is_file(FILES_DIR . '/layout/pagethumbnail/' . $exhibit_page->pagethumbnail)): ?>
                <a href="<?php echo WEB_FILES . '/layout/pagethumbnail/' . $exhibit_page->pagethumbnail; ?>" target="_blank">
                    <img src="<?php echo WEB_FILES . '/layout/pagethumbnail/' . $exhibit_page->pagethumbnail; ?>" class="exhibit-page-layout-icon"></a>
                <?php elseif($exhibit_page->layout == 'ddb-summary'): ?>
                    <img src="<?php echo WEB_FILES; ?>/layout/pagethumbnail/default-summary-icon.jpg" class="exhibit-page-layout-icon exhibit-page-layout-default-icon">
                <?php else: ?>
                    <img src="<?php echo WEB_FILES; ?>/layout/pagethumbnail/default-page-icon.jpg" class="exhibit-page-layout-icon exhibit-page-layout-default-icon">
                <?php endif; ?>
                <div style="clear:both; padding-top:10px;">
                <?php echo $this->formFile('pagethumbnail'); ?>
                </div>
                <div style="margin-top:10px;">
                    <?php echo $this->formCheckbox('deletePagethumbnail', 1); ?>
                    <?php echo $this->formLabel('deletePagethumbnail', __('Vorschaubild entfernen'),
                        array('style' => 'float:none;font-weight:normal;padding-left:4px;')); ?>
                </div>
            </div>
        </div>
    </fieldset>

    <fieldset id="layouts">
        <legend><?php echo __('Layouts'); ?></legend>

        <div id="layout-thumbs">
        <?php
            $layouts = exhibit_builder_get_layouts();
            foreach ($layouts as $layout) {
                echo exhibit_builder_layout($layout);
            }
        ?>
        </div>
    </fieldset>
    </div>
    <div id="save" class="three columns omega panel">
        <?php echo $this->formSubmit('save_page_metadata', __('Save Changes'), array('class'=>'submit big green button')); ?>
        <?php if ($exhibit_page->exists()): ?>
            <?php echo exhibit_builder_link_to_exhibit($exhibit, __('View Public Page'), array('class' => 'big blue button', 'target' => '_blank'), $exhibit_page); ?>
        <?php endif; ?>
        <div id="chosen_layout">
        <h4><?php echo __('Layout'); ?></h4>
        <?php
        if ($layout = $exhibit_page->layout) {
            echo exhibit_builder_layout($layout, false);
        } else {
            echo '<p>' . __('Choose a layout by selecting a thumbnail on the right.') . '</p>';
        }
        ?>
        </div>
    </div>
</form>
<script type="text/javascript" charset="utf-8">
//<![CDATA[

    jQuery(document).ready(function() {
        makeLayoutSelectable();
    });

    function makeLayoutSelectable() {
        //Make each layout clickable
        jQuery('div.layout').bind('click', function(e) {
            jQuery('#layout-thumbs').find('div.current-layout').removeClass('current-layout');
            jQuery(this).addClass('current-layout');

            // Remove the old chosen layout
            jQuery('#chosen_layout').find('div.layout').remove();
            jQuery('#chosen_layout').find('p').remove();

            // Copy the chosen layout
            var copyLayout = jQuery(this).clone();

            // Take the form input out of the copy (so no messed up forms).
            copyLayout.find('input').remove();

            // Change the id of the copy
            copyLayout.attr('id', 'chosen_' + copyLayout.attr('id')).removeClass('current-layout');

            // Append the copy layout to the chosen_layout div
            copyLayout.appendTo('#chosen_layout');

            // Check the radio input for the layout
            jQuery(this).find('input').attr('checked', true);

            // Grandgeorg Websolutions: add default icon switch
            var pageLayoutIcon = jQuery('.exhibit-page-layout-default-icon');
            if (pageLayoutIcon.length > 0) {
                if (jQuery(this).attr('id') == 'ddb-summary') {
                    pageLayoutIcon.attr('src', '<?php echo WEB_FILES; ?>/layout/pagethumbnail/default-summary-icon.jpg');
                } else {
                    pageLayoutIcon.attr('src', '<?php echo WEB_FILES; ?>/layout/pagethumbnail/default-page-icon.jpg');
                }
            }


        });
    }

    jQuery(window).load(function() {
        Omeka.ExhibitBuilder.wysiwyg();
    });

//]]>
</script>
<?php echo foot(); ?>
