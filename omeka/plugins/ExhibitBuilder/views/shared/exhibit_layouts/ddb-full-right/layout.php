<?php
// foreach (loop('files', $item->Files) as $file):
//     if ( ($file->hasThumbnail()){
//          $caption = (metadata($file,array('Dublin Core', 'Title'))) ?
//             '<strong>'.metadata($file, array('Dublin Core', 'Title')).'</strong>' : '';
//          file_markup($file, array('imageSize'=>'fullsize', 'linkToFile'=>'fullsize',
//             'linkAttributes'=>array('rel'=>'fancy_group', 'class'=>'fancyitem',
//             'title' => metadata('Item',array('Dublin Core', 'Title')),'caption'=>$caption)),
//             array('class' => 'square_thumbnail'));
//     }
// endforeach;

/******
* defined vars:
*    - exhibitPage
*******/

/******
* get exhibit from db (i.e. for  exhibit slug):
* Bad example as db has already been queried: $exhibit = get_db()->getTable('Exhibit')->find($exhibitPage->exhibit_id);
* Better: $exhibit = $exhibitPage->getExhibit();
*******/

/******
$attachment = exhibit_builder_page_attachment(1);
$file = $attachment['file'];
var_dump($file, $attachment);
var_dump($fileMeta, $attMeta, $attachment['caption']);
var_dump($exhibitPage);
*******/
?>
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
            <?php if ($attachment = exhibit_builder_page_attachment(1)): ?>
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
            <div class="gallery ddb-omeka-gallery">
                <?php echo ddb_exhibit_builder_thumbnail_gallery(2, 13,
                    array('class'=>'permalink'), 'square_thumbnail'); ?>
            </div>
            <?php if ($text = exhibit_builder_page_text(2)):?>
            <div class="exhibit-text">
                <?php echo $text; ?>
            </div>
            <?php endif; ?>
        </div>
        <?php if ($text = exhibit_builder_page_text(3)):?>
        <div class="ddb-omeka-subtitle"><?php echo $text; ?></div>
        <?php endif; ?>
        <?php if ($text = exhibit_builder_page_text(4)):?>
        <div class="ddb-omeka-two-col"><?php echo $text; ?></div>
        <?php endif; ?>
        <?php if ($text = exhibit_builder_page_text(5)):?>
        <div class="ddb-omeka-subtitle"><?php echo $text; ?></div>
        <?php endif; ?>
        <?php if ($text = exhibit_builder_page_text(6)):?>
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