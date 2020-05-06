<?php $attachment = exhibit_builder_page_attachment(1); ?>
<?php
if (!isset($pageoptions) || false === $pageoptions || !isset($pageoptions['boxpos'])) {
    $pageoptions['boxpos'] = 'mc';
}
if (!isset($pageoptions) || false === $pageoptions || !isset($pageoptions['bgpos'])) {
    $pageoptions['bgpos'] = 'mc';
}
$bgImgUrl = ExhibitDdbHelper::getFullsizeImageUrl($attachment);
$bgAttachmant = ExhibitDdbHelper::getBackgroundAttachment($attachment);
?>
<<?php echo $sectionTag; ?>
    data-color-palette="<?php echo $colorpalette; ?>"
    data-color-section="<?php echo $exhibitSection->backgroundcolor; ?>"
    <?php echo ($inSlider)? 'data-slideno="' . $slideCounter . '"' : ''; ?>
    class="<?php echo ($inSlider)? 'slide' : 'section'; ?> section-pos tile"
    id="se<?php echo $sectionCounter; ?><?php echo ($inSlider)? '-slide' . $slideCounter : ''; ?>"
    <?php echo (!empty($bgImgUrl))? ' style="background-image: url(' . $bgImgUrl . '); background-position: ' . $pageoptions['bgpos'] . ';"' : ''; ?>>

    <?php if ($bgAttachmant['type'] === 'ddb-video' && !empty($bgAttachmant['videoSrc']) && !empty($bgAttachmant['videoMimeType'])): ?>
    <video class="litfass-bg-video"
        <?php echo (isset($bgAttachmant['offsetStart']))? 'data-video-offset-start="' . $bgAttachmant['offsetStart'] . '"' : '';?>
        <?php echo (isset($bgAttachmant['offsetStop']))? 'data-video-offset-stop="' . $bgAttachmant['offsetStop'] . '"' : '';?>
        loop muted data-autoplay>
        <source src="<?php echo $bgAttachmant['videoSrc']; ?>" type="video/<?php echo $bgAttachmant['videoMimeType']; ?>">
    </video>
    <?php endif; ?>

    <?php if ($bgAttachmant['type'] === 'vimeo'):
    $vimeoVideoId = 'vimeo-s' . $sectionCounter . '-' . $bgAttachmant['info']['video_id'];
    $vimeoVideoId .= ($inSlider)? '-slide-' . $slideCounter : '';
    ?>
    <div class="litfass-bg-vimeo-video"
        id="<?php echo $vimeoVideoId; ?>"
        data-ddb-vimeo-id="<?php echo $bgAttachmant['info']['video_id']; ?>"
        data-ddb-vimeo-width="<?php echo $bgAttachmant['info']['width']; ?>"></div>
    <?php endif; ?>

    <?php if ($attachment): ?>
    <div class="boxpos-controls">
        <div class="control-info control-icon control-icon-right">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48" class="icon-info static-dark">
                <g class="icon-info-frame" transform="translate(-98 -31)">
                    <g transform="translate(57)">
                        <g>
                            <line x2="10" transform="translate(52.5 32.5) rotate(180)" stroke-linecap="square" />
                            <line x2="10" transform="translate(52.5 77.5) rotate(180)" stroke-linecap="square" />
                            <line y1="45" transform="translate(42.5 77.5) rotate(180)" stroke-linecap="square" />
                        </g>
                        <g transform="translate(130 110) rotate(180)">
                            <line x2="10" transform="translate(52.5 32.5) rotate(180)" stroke-linecap="square" />
                            <line x2="10" transform="translate(52.5 77.5) rotate(180)" stroke-linecap="square" />
                            <line y1="45" transform="translate(42.5 77.5) rotate(180)" stroke-linecap="square" />
                        </g>
                    </g>
                    <g transform="translate(-66.5 -365.5)" class="icon-info-i">
                        <line y2="18" transform="translate(188.5 415.5)" />
                        <line y2="4" transform="translate(188.5 407.5)" />
                    </g>
                </g>
                <g transform="translate(0 0)" class="icon-info-x">
                    <line class="icon-info-x-line" x2="18" y2="18" transform="translate(15 15)" />
                    <line class="icon-info-x-line" x2="18" y2="18" transform="translate(33 15) rotate(90)" />
                </g>
            </svg>
        </div>
        <?php if ((
                $bgAttachmant['type'] === 'ddb-video' &&
                !empty($bgAttachmant['videoSrc']) &&
                !empty($bgAttachmant['videoMimeType'])
            ) || (
                $bgAttachmant['type'] === 'vimeo' &&
                !empty($bgAttachmant['info'])
            )): ?>
        <div class="control-video">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48"
                class="icon-video-audio<?php echo ($bgAttachmant['type'] === 'vimeo')? '-vimeo' : ''; ?>">
            <g>
                <rect class="icon-video-audio-rect" width="48" height="48"/>
                <g transform="translate(-2)">
                <g transform="translate(-679 -756)">
                    <path class="icon-video-audio-speaker" d="M9773.369,2828.112l10.293,10.292-10.293,10.293Z" transform="translate(10479.031 3618.698) rotate(180)"/>
                    <rect class="icon-video-audio-speaker" width="9" height="10" transform="translate(692 775)"/>
                </g>
                <line class="icon-video-audio-volume" y2="10" transform="translate(30.5 19.5)"/>
                <line class="icon-video-audio-volume" y2="5" transform="translate(35.5 21.5)"/>
                </g>
                <g transform="translate(-1)">
                <line class="icon-video-audio-mute" x2="10" y2="10" transform="translate(29 19)"/>
                <line class="icon-video-audio-mute" x2="10" y2="10" transform="translate(39 19) rotate(90)"/>
                </g>
            </g>
            </svg>
        </div>
        <?php endif; ?>
        <?php if (false !== ($zoomImgUrl = ExhibitDdbHelper::getZoomable($attachment)) || ExhibitDdbHelper::isX3d($attachment)): ?>
        <div class="zoomer control-icon control-icon-right" <?php echo ExhibitDdbHelper::getZoomData($attachment); ?>>
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48" class="icon-zoom static-dark">
                <g transform="translate(-21 -84)">
                    <g transform="translate(-216.5 -429.5)">
                        <line class="icon-zoom-1" y2="10" transform="translate(259.5 529.5)" />
                        <line class="icon-zoom-1" x1="10" transform="translate(254.5 534.5)" />
                    </g>
                    <path class="icon-zoom-1" d="M2,2,12,12.286" transform="translate(48 110)" />
                    <g transform="translate(-20 53)">
                        <line class="icon-zoom-2" x2="10" transform="translate(52.5 32.5) rotate(180)" />
                        <line class="icon-zoom-2" x2="10" transform="translate(52.5 77.5) rotate(180)" />
                        <line class="icon-zoom-2" y1="45" transform="translate(42.5 77.5) rotate(180)" />
                    </g>
                    <g transform="translate(110 163) rotate(180)">
                        <line class="icon-zoom-2" x2="10" transform="translate(52.5 32.5) rotate(180)" />
                        <line class="icon-zoom-2" x2="10" transform="translate(52.5 77.5) rotate(180)" />
                        <line class="icon-zoom-2" y1="45" transform="translate(42.5 77.5) rotate(180)" />
                    </g>
                    <g class="icon-zoom-1" transform="translate(31 93)">
                        <circle class="icon-zoom-3" cx="12" cy="12" r="12" />
                        <circle class="icon-zoom-4" cx="12" cy="12" r="10.5" />
                    </g>
                </g>
            </svg>
        </div>
        <?php endif; ?>
        <div class="meta-scroll-controls meta-scroll-controls-fill d-none">
            <svg version="1.1" class="meta-scroll-arrow-up" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="22px" height="13px" viewBox="0 0 22 13" enable-background="new 0 0 22 13" xml:space="preserve">
                <path d="M20.61,12.04l-9.65-9.91l-9.91,9.91"></path>
            </svg>
            <br>
            <svg version="1.1" class="meta-scroll-mouse" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="23px" height="34px" viewBox="0 0 23 34" enable-background="new 0 0 23 34" xml:space="preserve">
                <path d="M1.52,11.17c0,0-1.55-9.71,9.93-9.71
                c11.48,0,9.93,9.71,9.93,9.71v11.58c0,0-0.64,9.71-9.93,9.71s-9.93-9.71-9.93-9.71V11.17z"></path>
                <line stroke-linecap="round" x1="11.51" y1="10.28" x2="11.51" y2="15.67"></line>
            </svg>
            <br>
            <svg version="1.1" class="meta-scroll-arrow-down" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="22px" height="13px" viewBox="-0 1 22 13" enable-background="new 0 1 22 13" xml:space="preserve">
                <path d="M1.05,2.13l9.65,9.91l9.91-9.91"></path>
            </svg>
        </div>
    </div>
    <?php endif; ?>
    <div class="section-container-pos pos-box-<?php echo $pageoptions['boxpos'] ?>">
        <div class="media-meta media-meta-standalone d-none">
            <div class="media-meta-scroll">
                <div class="media-meta-scroll-content">
                    <?php echo ExhibitDdbHelper::getItemInfo($attachment, $sectionCounter); ?>
                </div>
            </div>
        </div>
        <div class="pos-box">
            <div class="pos-box-scroll">
                <div class="pos-box-content ctxt">
                    <?php if ($exhibitSection->hide_title !== 1): ?>
                    <h3><?php echo htmlspecialchars(strip_tags($exhibitSection->title), ENT_QUOTES | ENT_HTML5); ?></h3>
                    <?php endif; ?>
                    <?php $pageText = exhibit_builder_page_text(1); ?>
                    <?php if (empty($pageText)): ?>
                    <h4><i>Bitte Text eingeben ...</i></h4>
                    <?php else: ?>
                    <?php echo $pageText; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</<?php echo $sectionTag; ?>>