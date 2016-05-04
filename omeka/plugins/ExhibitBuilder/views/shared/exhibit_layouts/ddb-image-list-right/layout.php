<div class="omeka-exhibit-content-wrapper clearfix">
    <div class="ddb-omeka-two-col-wrapper">
        <?php
        for ($i = 1; $i <= 8; $i++):
        $text = exhibit_builder_page_text($i);
        $attachment = exhibit_builder_page_attachment($i);
        if ($attachment || $text): ?>
        <div class="ddb-omeka-col-container">
            <div class="primary">
            <?php if ($text): ?>
                <div class="exhibit-text">
                    <?php echo $text; ?>
                </div>
            <?php endif; ?>
            </div>
            <div class="secondary">
            <?php if ($attachment): ?>
            <?php
            $videoShortcode = '';
            if (isset($attachment['item'])) {
                $videoShortcode = metadata($attachment['item'], array('Item Type Metadata', 'Videoquelle'));
            }
            if (!isset($attachment['file']) && empty($videoShortcode)) {
                $colorboxTriggerClass = '';
            } else {
                $colorboxTriggerClass = ' ddb-omeka-gallery';
            }
            ?>
                <div class="exhibit-item ddb-omeka-main-exhibit-item<?php echo $colorboxTriggerClass; ?>">
                    <?php echo ddb_exhibit_builder_attachment_markup($attachment); ?>
                </div>
            <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
        <?php endfor; ?>
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