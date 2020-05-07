<?php
$title = metadata('item', array('Item Type Metadata', 'Titel'));
if (empty($title)) { $title = metadata('item', array('Dublin Core', 'Title')); } ?>
<?php echo head(array('title' => $title, 'bodyclass' => 'item show ddb-full-item')); ?>
<h1><?php echo $title ?></h1>
<?php
$itemMetadata = all_element_texts('item', array('return_type' => 'array'));

// var_dump($itemMetadata);

// $metadataDisplayFields = array(
//     'Weiterer Titel', 'Institution', 'Link zum Objekt',
//     'Link zum Objekt bei der datenliefernden Einrichtung',
//     'Typ', 'Teil von', 'Beschreibung', 'Kurzbeschreibung',
//     'Thema', 'Beteiligte Personen und Organisationen',
//     'Zeit', 'Ort', 'Maße/Umfang', 'Material/Technik',
//     'Sprache', 'Identifikator', 'Anmerkungen', 'Förderung',
//     'Rechtsstatus', 'Videoquelle'
// );

/**
 * Display DDB style Metadata
 */
$metadataDisplayFields = array(
    'Weiterer Titel', 'Link zum Objekt in der DDB',
    'Link zum Objekt bei der datengebenden Institution',
    'Typ', 'Teil von', 'Beschreibung', 'Kurzbeschreibung',
    'Thema', 'Beteiligte Personen und Organisationen',
    'Zeit', 'Ort', 'Maße/Umfang', 'Material/Technik',
    'Sprache', 'Identifikator', 'Förderung',
    'Rechtsstatus'
);

if (isset($itemMetadata['VA DDB Item Type Metadata'])):

    $institution = '';
    if (isset($itemMetadata['VA DDB Item Type Metadata']['Name der Institution'][0]) &&
        !empty($itemMetadata['VA DDB Item Type Metadata']['Name der Institution'][0]) &&
        isset($itemMetadata['VA DDB Item Type Metadata']['URL der Institution'][0]) &&
        !empty($itemMetadata['VA DDB Item Type Metadata']['URL der Institution'][0]))
    {
        $institution = '<a href="'
            . strip_tags($itemMetadata['VA DDB Item Type Metadata']['URL der Institution'][0])
            . '" target="_blank">'
            . html_escape(strip_tags($itemMetadata['VA DDB Item Type Metadata']['Name der Institution'][0]))
            . '</a>';
    } elseif (isset($itemMetadata['VA DDB Item Type Metadata']['Name der Institution'][0]) &&
        !empty($itemMetadata['VA DDB Item Type Metadata']['Name der Institution'][0]) &&
        1 === preg_match('|href="([^"]*)|', $itemMetadata['VA DDB Item Type Metadata']['Name der Institution'][0], $matches))
    {
        $institution = '<a href="'
            . $matches[1]
            . '" target="_blank">'
            . html_escape(strip_tags($itemMetadata['VA DDB Item Type Metadata']['Name der Institution'][0]))
            . '</a>';
    } elseif (isset($itemMetadata['VA DDB Item Type Metadata']['Name der Institution'][0]) &&
        !empty(strip_tags($itemMetadata['VA DDB Item Type Metadata']['Name der Institution'][0]))
    ) {
        $institution = strip_tags($itemMetadata['VA DDB Item Type Metadata']['Name der Institution'][0]);
    }

    foreach ($itemMetadata['VA DDB Item Type Metadata'] as $metaName => $metaValue):
        if(in_array($metaName, $metadataDisplayFields)): ?>
        <div id="<?php echo text_to_id(html_escape('VA DDB Item Type Metadata' . $metaName)); ?>" class="element">
            <h3><?php echo html_escape(__($metaName)); ?></h3>
            <?php foreach ($metaValue as $metaText): ?>
            <?php if ($metaName == 'Rechtsstatus'): ?>
            <div class="element-text"><?php echo ExhibitDdbHelper::getLicenseFromShortcode($metaText); ?></div>
            <?php elseif($metaName == 'Link zum Objekt bei der datengebenden Institution' || $metaName == 'Link zum Objekt in der DDB'): ?>
            <?php
            $href = strip_tags($metaText);
            $dom = new DOMDocument();
            @$dom->loadHTML($metaText);
            @$tags = $dom->getElementsByTagName('a');
            foreach ($tags as $tag) {
               $href =  $tag->getAttribute('href');
               break;
            }
            ?>
            <div class="element-text"><a href="<?php echo $href; ?>"><?php echo $href; ?></a></div>
            <?php else: ?>
            <div class="element-text"><?php echo $metaText; ?></div>
            <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php elseif (!empty($institution) && $metaName == 'Name der Institution'): ?>
        <div id="<?php echo text_to_id(html_escape('VA DDB Item Type Metadata' . $metaName)); ?>" class="element">
            <h3>Institution</h3>
            <div class="element-text"><?php echo $institution; ?></div>
        </div>
<?php endif; endforeach; endif; ?>

<?php
/**
 * Display files
 */
$containerMinWidth = 280;
$containerMinHeight = 100;
$width = 0; $height = 0;
$files = $item->getFiles();
// var_dump($files);
$countFiles = count($files);
$additionalWrapperOpen = '';
$additionalWrapperClose = '';
$wrapperAttributes = null;
foreach ($files as $file) {
    if (isset($file->metadata)) {
        $metadata = json_decode($file->metadata);
        if (isset($metadata->video->resolution_x) &&
            $metadata->video->resolution_x > $width) {
            $width = $metadata->video->resolution_x;
        } else {
            $width = 280;
        }
        if (isset($metadata->video->resolution_y) &&
            $metadata->video->resolution_y > 0) {
            $height = $height + $metadata->video->resolution_y;
        } else {
            $height = 100;
        }
        if (isset($metadata->mime_type) &&
            ($metadata->mime_type == 'audio/mpeg' ||
                $metadata->mime_type == 'application/ogg')) {
                $additionalWrapperOpen = '<audio controls>';
                $additionalWrapperClose = '</audio>';
                $wrapperAttributes = array();
                $width = 540;
        }
    }
}
// Video
$embedVideo = '';
$videoWidth = 0;
$videoHeight = 0;
$metaDataVideoSource = '';
if (isset($itemMetadata['VA DDB Item Type Metadata']['Videoquelle']) && isset($itemMetadata['VA DDB Item Type Metadata']['Videoquelle'][0])) {
    $metaDataVideoSource = $itemMetadata['VA DDB Item Type Metadata']['Videoquelle'][0];
}
if (!empty($metaDataVideoSource)) {
    $videoImage = '';
    $videoImages = $item->getFiles();
    if (isset($videoImages) && !empty($videoImages) && is_array($videoImages) &&
        isset($videoImages[0]) && is_object($videoImages[0])) {

        $videoImage = html_escape($videoImages{0}->getWebPath('fullsize'));
    }
    $embedVideo = ExhibitDdbHelper::getVideoFromShortcode($metaDataVideoSource, $videoImage);
    if (!empty($embedVideo) && !empty(ExhibitDdbHelper::$videoVimeoInfo)) {
        $containerMinWidth = 500;
        $containerMinHeight = 281;
        $videoInfo = ExhibitDdbHelper::$videoVimeoInfo;
        if(isset($videoInfo[0]['width']) && !empty($videoInfo[0]['width'])) {
            $videoWidth = $videoInfo[0]['width'];
        }
        if(isset($videoInfo[0]['height']) && !empty($videoInfo[0]['height'])) {
            $videoHeight = $videoInfo[0]['height'];
        }
    }
}

// imgAttributes
$imgAttributes = array();

?>
<?php
/**
 * echo the output if any
 */
if (metadata('item', 'has files') || !empty($embedVideo)): ?>
<div id="itemfiles" class="element">
    <h3><?php echo __('Files'); ?></h3>
    <div class="element-text ddb-omeka-itempage-full-item-container">
    <?php echo $embedVideo; ?>
    <?php if(empty($embedVideo)): ?>
        <?php echo $additionalWrapperOpen; ?>
        <?php
        if (isset($wrapperAttributes)) {
            echo files_for_item(array(
                'imageSize' => 'fullsize',
                'linkToFile' => false,
                'imgAttributes'=> $imgAttributes
            ), $wrapperAttributes);
        } else {
            echo files_for_item(array(
                'imageSize' => 'fullsize',
                'linkToFile' => false,
                'imgAttributes'=> $imgAttributes
            ));
        }
        ?>
        <?php echo $additionalWrapperClose; ?>
    <?php endif; ?>
    </div>
</div>
<?php elseif (($x3d = get_db()->getTable('X3d')->findByItemId($item->id))):
    $x3dDir = FILES_DIR . DIRECTORY_SEPARATOR . 'x3d' . DIRECTORY_SEPARATOR . $x3d->directory;
    $x3dWebdir = WEB_FILES . '/x3d/' . $x3d->directory;
?>
<h3>3D Objekt</h3>
<div style="width:100%; height:100%; margin:0; padding:0; overflow:hidden;">
    <x3d id="x3dElement" style="width:100%; height:100%; border:0; margin:0; padding:0;">
        <scene>
            <inline url="<?php echo $x3dWebdir . '/' . $x3d->x3d_file; ?>"> </inline>
        </scene>
    </x3d>
</div>
<div>Verwenden Sie das Mausrad oder die rechte Maustaste, um das Objekt zu zoomen</div>
<?php endif; ?>
<div id="item-citation" class="element">
    <h3>Quellenangabe</h3>
    <div class="element-text"><?php echo metadata('item', 'citation', array('no_escape' => true)); ?></div>
</div>
<?php fire_plugin_hook('public_items_show', array('view' => $this, 'item' => $item)); ?>
<nav>
<ul class="omeka-item-pagination navigation inline">
    <li id="previous-item" class="omeka-item-previous"><?php echo link_to_previous_item_show(); ?></li>
    <li id="next-item" class="omeka-item-next"><?php echo link_to_next_item_show(); ?></li>
</ul>
</nav>
<script>
    $(document).ready(function() {
        /* GINA Grandgeorg Internet Application object */
        if ($.Gina) {
            $.Gina = $.Gina;
        } else {
            $.Gina = {}
        }

        $.Gina.offsetX = 63;
        $.Gina.offsetY = 95;
        $.Gina.winW = 0;
        $.Gina.winH = 0;

        $.Gina.setWindowSizes = function(loaded) {
            this.winW = $(window).innerWidth();
            this.winH = $(window).innerHeight();
            if (this.winH < $(window).height() && this.winW < $(window).width()) {
                this.winW = $(window).width();
                this.winH = $(window).height();
            }
            this.winW = this.winW - this.offsetX;
            this.winH = this.winH - this.offsetY;
            if(!loaded) {
                $(window).resize(function() {
                    $.Gina.setWindowSizes(!loaded);
                });
            }
        };

        $.Gina.setWindowSizes();

        $.Gina.sizeColorBoxItem = function(loaded) {

            var mediaWidth = <?php echo $width; ?>;
            var mediaHeight = <?php echo $height; ?>;
            var countFiles =  <?php echo $countFiles; ?>;
            var withType = '';
            var newHeight = 0;
            var newWidth = 0;
            var checkWidth = 0;
            var videoWidth = <?php echo $videoWidth; ?>;
            var videoHeight = <?php echo $videoHeight; ?>;


            // check if we have video media
            if (0 == mediaWidth && 0 == mediaHeight && 0 < videoWidth && 0 < videoHeight) {
                mediaWidth = videoWidth;
                mediaHeight = videoHeight;
            }

            // container width
            if (mediaWidth > $.Gina.winW) {
                newWidth = $.Gina.winW;
                withType = 'window';
            } else {
                newWidth = mediaWidth;
            }

            // container height
            newHeight = mediaHeight / mediaWidth * newWidth;
            checkWidth = mediaWidth / mediaHeight * newHeight;
            if (newHeight > $.Gina.winH) {
                newHeight = $.Gina.winH;
                checkWidth = mediaWidth / mediaHeight * newHeight;
            }
            if (checkWidth < newWidth && countFiles == 1) {
                newWidth = checkWidth;
            }

            if(!loaded) {
                $(window).resize(function() {
                    $.Gina.sizeColorBoxItem(!loaded);
                });
            } else {
                $.colorbox.resize({width: (newWidth + 63), height: (newHeight + 95)});
            }
        }
        $.Gina.sizeColorBoxItem();
    });
</script>
<?php echo foot(array(), 'item-footer'); ?>
