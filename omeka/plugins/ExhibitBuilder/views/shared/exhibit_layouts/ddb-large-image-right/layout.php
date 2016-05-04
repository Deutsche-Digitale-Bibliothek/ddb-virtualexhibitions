<div class="omeka-exhibit-content-wrapper clearfix">
    <div class="ddb-omeka-two-col-wrapper">
        <div class="primary">
            <?php if ($text = exhibit_builder_page_text(1)):?>
            <div class="exhibit-text">
                <?php echo $text; ?>
            </div>
            <?php endif; ?>
        </div>
        <div class="secondary">
            <div class="ddb-omeka-gallery-large-size ddb-omeka-gallery">
                <?php for ($i=1; $i < 9; $i++): ?>
                <?php if ($attachment = exhibit_builder_page_attachment($i)): ?>
                <?php echo ddb_exhibit_builder_attachment_markup($attachment); ?>
                <?php endif; ?>
                <?php endfor; ?>
            </div>
            <?php if ($text = exhibit_builder_page_text(2)):?>
            <div class="exhibit-text">
                <?php echo $text; ?>
            </div>
            <?php endif; ?>
        </div>
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