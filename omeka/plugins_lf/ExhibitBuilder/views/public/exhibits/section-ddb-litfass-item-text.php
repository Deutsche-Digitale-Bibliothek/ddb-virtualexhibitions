<?php $attachment = exhibit_builder_page_attachment(1); ?>
<?php
if (!isset($pageoptions) || false === $pageoptions || !isset($pageoptions['align'])) {
    $pageoptions['align'] = 'right';
}
?>
<<?php echo $sectionTag; ?>
    data-color-palette="<?php echo $colorpalette; ?>"
    data-color-section="<?php echo $exhibitSection->backgroundcolor; ?>"
    <?php echo ($inSlider)? 'data-slideno="' . $slideCounter . '"' : ''; ?>
    class="<?php echo ($inSlider)? 'slide' : 'section'; ?> section-text-media <?php echo ($pageoptions['align'] === 'left')? 'section-text-media-right' : 'section-text-media-right'; ?> <?php echo $colors[$exhibitSection->backgroundcolor]['type']; ?> tile"
    id="se<?php echo $sectionCounter; ?><?php echo ($inSlider)? '-slide' . $slideCounter : ''; ?>">
    <div class="section-container container-fluid">
        <div class="row">
            <div class="col-md-6 col-media<?php echo ($pageoptions['align'] === 'left')? ' order-md-last' : ''; ?>">
                <div class="container-media">
                    <div class="content-controls<?php echo ($pageoptions['align'] === 'left')? ' order-md-last' : ''; ?>">
                        <div class="control-info control-icon<?php echo ($pageoptions['align'] === 'left')? ' control-icon-right' : ' control-icon-left'; ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48" class="icon-info">
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
                        <div class="control-zoom control-icon<?php echo ($pageoptions['align'] === 'left')? ' control-icon-right' : ' control-icon-left'; ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48" class="icon-zoom">
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
                    <div class="content-media<?php echo ($pageoptions['align'] === 'left')? ' order-md-first' : ''; ?>">
                        <div class="media-meta d-none<?php echo ($pageoptions['align'] === 'left')? '  order-md-last' : ''; ?>">
                            <div class="media-meta-scroll">
                                <div class="media-meta-scroll-content">
                                    <?php echo ExhibitDdbHelper::getItemInfo($attachment, $sectionCounter); ?>
                                </div>
                            </div>
                        </div>
                        <div class="media-item-container<?php echo ($pageoptions['align'] === 'left')? ' order-md-first' : ''; ?>">
                            <?php
                            $attachmentMarkup = ExhibitDdbHelper::getAttachmentMarkup(
                                $attachment,
                                array('class' => 'media-item'),
                                true,
                                'middsize'
                            );
                            ?>
                            <?php if (empty($attachmentMarkup)): ?>
                            <h4 style="margin: 1rem 2rem;"><i>Bitte Objekt einfügen ...</i></h4>
                            <?php else: ?>
                            <?php echo $attachmentMarkup; ?>
                            <?php endif; ?>
                            <div class="media-item-caption<?php echo ($pageoptions['align'] === 'left')? ' media-item-caption-right' : ' media-item-caption-left'; ?>">
                                <?php //echo ExhibitDdbHelper::getItemDescription($attachment, null); ?>
                                <?php echo ($attachment['caption'])? strip_tags(htmlentities($attachment['caption'], ENT_COMPAT | ENT_HTML5, 'UTF-8', false)) : ''; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-text<?php echo ($pageoptions['align'] === 'left')? ' order-md-first' : ''; ?>">
                <div class="scroll-container">
                    <?php ob_start(); ?>
                    <div class="scroll-controls<?php echo ($pageoptions['align'] === 'left')? ' scroll-controls-left' : ''; ?>">
                        <svg version="1.1" class="scroll-arrow-up" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                            x="0px" y="0px" width="22px" height="13px" viewBox="0 0 22 13" enable-background="new 0 0 22 13"
                            xml:space="preserve">
                            <path d="M20.61,12.04l-9.65-9.91l-9.91,9.91" />
                        </svg>
                        <br>
                        <svg version="1.1" class="scroll-mouse" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                            x="0px" y="0px" width="23px" height="34px" viewBox="0 0 23 34" enable-background="new 0 0 23 34"
                            xml:space="preserve">
                            <path d="M1.52,11.17c0,0-1.55-9.71,9.93-9.71
                            c11.48,0,9.93,9.71,9.93,9.71v11.58c0,0-0.64,9.71-9.93,9.71s-9.93-9.71-9.93-9.71V11.17z" />
                            <line stroke-linecap="round" x1="11.51" y1="10.28"
                                x2="11.51" y2="15.67" />
                        </svg>
                        <br>
                        <svg version="1.1" class="scroll-arrow-down" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                            x="0px" y="0px" width="22px" height="13px" viewBox="-0 1 22 13" enable-background="new 0 1 22 13"
                            xml:space="preserve">
                            <path d="M1.05,2.13l9.65,9.91l9.91-9.91" />
                        </svg>
                    </div>
                    <?php $scrollControls = ob_get_clean(); ?>
                    <?php if ($pageoptions['align'] === 'left') { echo $scrollControls; } ?>
                    <div class="text-content">
                        <div class="fader"></div>
                        <div class="scroll-frame">
                            <div class="scroll-element">
                                <div class="scroll-element-inner">
                                    <?php if ($exhibitSection->hide_title !== 1): ?>
                                    <h1><?php echo htmlspecialchars(strip_tags($exhibitSection->title), ENT_QUOTES | ENT_HTML5); ?></h1>
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
                    <?php if ($pageoptions['align'] === 'right') { echo $scrollControls; } ?>
                </div>
            </div>
        </div>
    </div>
</<?php echo $sectionTag; ?>>