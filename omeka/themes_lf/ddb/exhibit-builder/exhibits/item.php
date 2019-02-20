<?php
$containerMinWidth = 280;
$containerMinHeight = 100;
$width = 0; $height = 0;
$files = $item->getFiles();
$countFiles = count($files);
$additionalWrapperOpen = '';
$additionalWrapperClose = '';
$wrapperAttributes = array('class'=>'inline-lightbox-element');
foreach ($files as $file) {
    if (isset($file->metadata)) {
        $metadata = json_decode($file->metadata);
        // var_dump($metadata);
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
                $metadata->mime_type == 'application/ogg'))
        {
            $additionalWrapperOpen = '<audio controls>';
            $additionalWrapperClose = '</audio>';
            $wrapperAttributes = array();
            $width = 540;
        }
    }
}
$x3dMarkup = '';
$x3dMarkupIframeOpen = '';
$x3dMarkupIframeClose = '';
$containerStyles = '';
// 1 != 1 &&
// if (!$files && ($x3d = get_db()->getTable('X3d')->findByItemId($item->id))):
//     $x3dDir = FILES_DIR . DIRECTORY_SEPARATOR . 'x3d' . DIRECTORY_SEPARATOR . $x3d->directory;
//     $x3dWebdir = WEB_FILES . '/x3d/' . $x3d->directory;
//     $containerMinWidth = 500;
//     $containerMinHeight = 400;
//     $x3dMarkup = '
//     <div style="width:100%; height:350px; margin:0; padding:0; overflow:hidden;">
//         <x3d id="x3dElement" style="width:100%; height:100%; border:0; margin:0; padding:0;">
//             <scene>
//                 <inline url="' . $x3dWebdir . '/' . $x3d->x3d_file . '"> </inline>
//             </scene>
//         </x3d>
//     </div>
//     <div>Verwenden Sie das Mausrad, um das Objekt zu zoomen.</div>
//     <script type="text/javascript" src="http://127.0.0.1:8080/public/plugins/x3d/views/shared/javascripts/x3dom.js"></script>
//     ';
// endif;
// var_dump(get_db()->getTable('X3d'));
// var_dump(method_exists(get_db()->getTable('X3d'), 'findByItemId'));
// $foo = get_db();
// $tblx3d = $foo->getTable('X3d');
// var_dump($foo);
// var_dump($tblx3d);
// var_dump(get_class_methods($foo));
// var_dump(get_class_methods($tblx3d));
// var_dump(get_class_methods(get_db()->getTable('X3d')));

// return;

if (!$files && ($x3d = get_db()->getTable('X3d')->findByItemId($item->id))):
    $x3dDir = FILES_DIR . DIRECTORY_SEPARATOR . 'x3d' . DIRECTORY_SEPARATOR . $x3d->directory;
    $x3dWebdir = WEB_FILES . '/x3d/' . $x3d->directory;
    $containerMinWidth = 320;
    $containerMinHeight = 320;
    $containerStyles = ' width:100%; height:100%;';
    queue_js_file('vendor/jquery.min');
    $x3dMarkupIframeOpen = '
    <!DOCTYPE html>
    <html lang="de-DE" style="width:100%; height:100%; border:0; margin:0; padding:0;">
    <head>
    <link type="text/css" rel="stylesheet" media="all" href="' . WEB_PLUGIN . '/X3d/views/shared/css/x3d-public.css">
    <script type="text/javascript" src="' . WEB_THEME . '/ddb/javascripts/vendor/jquery.min.js"></script>
    '
    // . head_js()
    // <script type="text/javascript" src="http://www.x3dom.org/x3dom/release/x3dom.js"></script>
    . '
    <script type="text/javascript" src="' . WEB_PLUGIN . '/X3d/views/shared/javascripts/x3dom.js"></script>
    </head>
    <body style="width:100%; height:100%; border:0; margin:0; padding:0;">
    ';
    $x3dMarkupIframeClose = '</body></html>';
    $x3dMarkup = '
        <x3d id="x3dElement" style="width:100%; height:100%; border:0; margin:0; padding:0;">
            <scene>
                <inline url="' . $x3dWebdir . '/' . $x3d->x3d_file . '"> </inline>
            </scene>
        </x3d>
    <div style="margin:0 auto; left:0; right:0; z-index:10; position:absolute; bottom:0; text-align:center; background:rgba(0,0,0,9.5); color:#fff;">Verwenden Sie das Mausrad oder die rechte Maustaste, um das Objekt zu zoomen.</div>
    ';
endif;


// Video
$embedVideo = '';
$videoWidth = 0;
$videoHeight = 0;
$metaDataVideoSource = metadata($item, array('Item Type Metadata', 'Videoquelle'));
if (!empty($metaDataVideoSource)) {
    $videoImage = '';
    if (isset($file) && !empty($file) && is_object($file)) {
        $videoImage = html_escape($file->getWebPath('fullsize'));
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
    } elseif (!empty($embedVideo) && !empty(ExhibitDdbHelper::$videoDdbInfo)) {
        $containerMinWidth = 500;
        $containerMinHeight = 284;
        $videoInfo = ExhibitDdbHelper::$videoDdbInfo;
        if(isset($videoInfo[0]['width']) && !empty($videoInfo[0]['width'])) {
            $videoWidth = $videoInfo[0]['width'];
        }
        if(isset($videoInfo[0]['height']) && !empty($videoInfo[0]['height'])) {
            $videoHeight = $videoInfo[0]['height'];
        }
    }
    // Reset $width and $height if we have video source and file image (as thubnail or gallery image)
    $width = 0;
    $height = 0;
}

// imgAttributes
$imgAttributes = array();
if (isset($file) && !empty($file) && is_object($file)) {
    $itemTitle = ExhibitDdbHelper::getItemTitle(array('item' => $item), $file);
    if ($itemTitle) {
        $imgAttributes = array(
            'alt'   => $itemTitle,
            'title' => $itemTitle
        );
    }
}

// image map
$imagemap = metadata($item, array('Item Type Metadata', 'Imagemap'), array('no_escape' => true, 'no_filter' => true));
$usemap = array();
if (!empty($imagemap)) {
    $usemap = array(
        'data-mediawidth' => (string) $width,
        'data-mediaheight' => (string) $height,
        'usemap' => "#imageMap",
        'id' => 'ddb-imagemap-image'
    );
    $imgAttributes = array_merge($imgAttributes, $usemap);
}
?>
<?php

// ------------------------------ OUTPUT ------------------------------ //

?>
<?php echo $x3dMarkupIframeOpen; ?>
<div class="inline-lightbox-container" style="min-width: <?php
    echo $containerMinWidth; ?>px; min-height: <?php
    echo $containerMinHeight; ?>px;<?php
    echo $containerStyles; ?>">
<?php echo $x3dMarkup; ?>
<?php echo $embedVideo; ?>
<?php if(empty($embedVideo)): ?>
<?php echo $additionalWrapperOpen; ?>
<?php echo files_for_item(array(
        'imageSize' => 'fullsize',
        'linkToFile' => false,
        'imgAttributes'=> $imgAttributes
        ),
    $wrapperAttributes); ?>
<?php echo $additionalWrapperClose; ?>
<?php endif; ?>
</div>

<?php
if (!empty($imagemap)) {
    echo $imagemap;
    echo js_tag('vendor/jquery.rwdImageMaps');
}
?>

<script type="text/javascript">
    $(document).ready(function() {

    <?php if (!empty($imagemap)): ?>
        $('map').imgmapinfo();
    <?php endif; ?>

        if (typeof $.Gina == 'undefined') {
            $.Gina = {};
        };
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
            var isIframe = <?php echo (empty($x3dMarkup))? '\'no\'' : '\'yes\''; ?>;
            var minWidth = <?php echo $containerMinWidth; ?>;

            if (isIframe == 'yes') { return; };

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
            if (newWidth > minWidth) {
                minWidth = newWidth;
            }

            // set container
            if (withType == 'window') {
                $('.inline-lightbox-container').css({'width': newWidth, 'min-width': minWidth, margin: '0 auto'});
            } else {
                $('.inline-lightbox-container').css({'width': newWidth, 'min-width': minWidth, margin: '0 auto'});
            }
            $('.inline-lightbox-container').css({'height': newHeight});

            // set img & extarnal media
            if ($('.inline-lightbox-element img').get(0)) {
                $('.inline-lightbox-element img').css({'max-height': newHeight, 'max-width': newWidth});
            }
            if ($('.inline-lightbox-container iframe').get(0)) {
                $('.inline-lightbox-container iframe').attr({'width': newWidth, 'height' : newHeight});
                // $('.inline-lightbox-container iframe')[0].setAttribute({'width': newWidth, 'height' : newHeight});
            }

            if(!loaded) {
                $(window).resize(function() {
                    $.Gina.sizeColorBoxItem(!loaded);
                });
            } else {
                $.colorbox.resize({width: (newWidth + 63), height: (newHeight + 95)});
            }

            <?php if (!empty($imagemap)): ?>
            $('#ddb-imagemap-image').rwdImageMaps(newWidth, newHeight);
            // imagemap.rwdImageMap;
            // console.log('called gina window ready');
            <?php endif; ?>
        }
        $.Gina.sizeColorBoxItem();


    });

</script>
<?php echo $x3dMarkupIframeClose; ?>