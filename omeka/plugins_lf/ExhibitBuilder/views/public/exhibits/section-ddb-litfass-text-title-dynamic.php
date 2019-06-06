<?php $attachment = exhibit_builder_page_attachment(1); ?>
<?php
if (!isset($pageoptions) || false === $pageoptions || !isset($pageoptions['align'])) {
    $pageoptions['align'] = 'right';
}
if (!isset($pageoptions) || false === $pageoptions || !isset($pageoptions['vertical-align'])) {
    $pageoptions['vertical-align'] = 'top';
}
?>
<<?php echo $sectionTag; ?>
    data-color-palette="<?php echo $colorpalette; ?>"
    data-color-section="<?php echo $exhibitSection->backgroundcolor; ?>"
    <?php echo ($inSlider)? 'data-slideno="' . $slideCounter . '"' : ''; ?>
    class="<?php echo ($inSlider)? 'slide' : 'section'; ?> section-text-special-02 <?php echo $colors[$exhibitSection->backgroundcolor]['type']; ?> tile"
    id="se<?php echo $sectionCounter; ?><?php echo ($inSlider)? '-slide' . $slideCounter : ''; ?>">
    <div class="section-container container-fluid">
        <div class="row">
            <div class="col-md-6 col-title col-<?php echo $pageoptions['vertical-align'] ?><?php echo ($pageoptions['align'] === 'left')? ' order-md-last' : ''; ?>">
                <?php if ($exhibitSection->hide_title !== 1): ?>
                <h2><?php echo htmlspecialchars(strip_tags($exhibitSection->title), ENT_QUOTES | ENT_HTML5); ?></h2>
                <?php endif; ?>
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
                                <?php $pageText = exhibit_builder_page_text(1); ?>
                                <?php if (empty($pageText)): ?>
                                <h4><i>Bitte Text eingeben ...</i></h4>
                                <?php else: ?>
                                <?php echo $pageText; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php if ($pageoptions['align'] === 'right') { echo $scrollControls; } ?>
                </div>
            </div>
        </div>
    </div>
</<?php echo $sectionTag; ?>>