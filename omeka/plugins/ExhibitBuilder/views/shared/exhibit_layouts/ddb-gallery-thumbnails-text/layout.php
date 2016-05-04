<div class="omeka-exhibit-content-wrapper clearfix">
    <div class="ddb-omeka-two-col-wrapper">
        <?php if ($text = exhibit_builder_page_text(1)):?>
        <div class="ddb-omeka-two-col"><?php echo $text; ?></div>
        <?php endif; ?>
        <div class="ddb-omeka-cover-two-col">
            <div class="gallery ddb-omeka-gallery">
                <?php echo ddb_exhibit_builder_thumbnail_gallery(1, 24,
                    array('class'=>'permalink'), 'square_thumbnail'); ?>
            </div>
        </div>
        <?php if ($text = exhibit_builder_page_text(2)):?>
        <div class="ddb-omeka-subtitle"><?php echo $text; ?></div>
        <?php endif; ?>
        <?php if ($text = exhibit_builder_page_text(3)):?>
        <div class="ddb-omeka-two-col"><?php echo $text; ?></div>
        <?php endif; ?>
    </div>
    <div class="tertiary">
        <?php $exhibit = $exhibitPage->getExhibit(); ?>
        <?php if (isset($exhibit->widget_top_first) && !empty($exhibit->widget_top_first)): ?>
        <div class="ddb-omeka-exhibit-widget-wrapper"><div class="ddb-omeka-exhibit-widget ddb-omeka-exhibit-widget-top-first"><?php echo $exhibit->widget_top_first; ?></div></div>
        <?php endif; ?>
        <?php if (isset($exhibit->widget_top_second) && !empty($exhibit->widget_top_second)): ?>
        <div class="ddb-omeka-exhibit-widget-wrapper"><div class="ddb-omeka-exhibit-widget ddb-omeka-exhibit-widget-top-second"><?php echo $exhibit->widget_top_second; ?></div></div>
        <?php endif; ?>
        <?php if (isset($exhibit->banner) && !empty($exhibit->banner) &&
            file_exists(FILES_DIR . '/layout/banner/' . $exhibit->banner)): ?>
        <img src="<?php echo WEB_FILES . '/layout/banner/'
            . $exhibit->banner; ?>" alt="exihibition banner" class="exhibition-banner">
        <?php endif; ?>
        <?php if (isset($exhibitPage->widget) && !empty($exhibitPage->widget)): ?>
        <div class="ddb-omeka-exhibit-widget-wrapper"><div class="ddb-omeka-exhibit-widget ddb-omeka-exhibit-widget-page"><?php echo $exhibitPage->widget; ?></div></div>
        <?php endif; ?>
        <?php if (isset($exhibit->widget) && !empty($exhibit->widget)): ?>
        <div class="ddb-omeka-exhibit-widget-wrapper"><div class="ddb-omeka-exhibit-widget ddb-omeka-exhibit-widget-exhibit"><?php echo $exhibit->widget; ?></div></div>
        <?php endif; ?>
    </div>
</div>