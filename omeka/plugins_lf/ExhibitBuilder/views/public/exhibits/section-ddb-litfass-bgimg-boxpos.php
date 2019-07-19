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
    <video class="litfass-bg-video" loop muted data-autoplay>
        <source src="<?php echo $bgAttachmant['videoSrc']; ?>" type="video/<?php echo $bgAttachmant['videoMimeType']; ?>">
    </video>
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
                <div class="pos-box-content">
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