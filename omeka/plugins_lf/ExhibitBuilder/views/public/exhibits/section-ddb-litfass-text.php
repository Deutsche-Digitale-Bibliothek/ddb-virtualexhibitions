<?php $attachment = exhibit_builder_page_attachment(1); ?>
<<?php echo $sectionTag; ?>
    data-color-palette="<?php echo $colorpalette; ?>"
    data-color-section="<?php echo $exhibitSection->backgroundcolor; ?>"
    <?php echo ($inSlider)? 'data-slideno="' . $slideCounter . '"' : ''; ?>
    class="<?php echo ($inSlider)? 'slide' : 'section'; ?> <?php echo $colors[$exhibitSection->backgroundcolor]['type']; ?> tile"
    id="se<?php echo $sectionCounter; ?><?php echo ($inSlider)? '-slide' . $slideCounter : ''; ?>">
    <div class="section-container container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="scroll-container">
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
                    <div class="scroll-controls">
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
                </div>
            </div>
        </div>
    </div>
</<?php echo $sectionTag; ?>>