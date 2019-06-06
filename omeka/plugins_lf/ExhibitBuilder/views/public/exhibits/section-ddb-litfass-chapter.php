<?php
$attachment = exhibit_builder_page_attachment(1);
// $bgImgUrl = ExhibitDdbHelper::getOriginalImageUrl($attachment);
$bgImgUrl = ExhibitDdbHelper::getFullsizeImageUrl($attachment);
$chapterCounter++;
?>
<<?php echo $sectionTag; ?>
    data-color-palette="<?php echo $colorpalette; ?>"
    data-color-section="<?php echo $exhibitSection->backgroundcolor; ?>"
    <?php echo ($inSlider)? 'data-slideno="' . $slideCounter . '"' : ''; ?>
    class="<?php echo ($inSlider)? 'slide' : 'section'; ?> <?php echo $colors[$exhibitSection->backgroundcolor]['type']; ?> section-chapter tile"
    id="se<?php echo $sectionCounter; ?><?php echo ($inSlider)? '-slide' . $slideCounter : ''; ?>"
    <?php echo (!empty($bgImgUrl))? ' style="background-image: url(' . $bgImgUrl . ')"' : ''; ?>>
    <div class="section-container container-fluid">
        <div class="row auto">
            <div class="col-sm-3 chapter-num">
                <h3>
                <?php echo (!empty($bgImgUrl))? '<span>' : ''; ?>
                <?php echo ExhibitDdbHelper::getLeadingZeroNum($chapterCounter); ?>
                <?php echo (!empty($bgImgUrl))? '</span>' : ''; ?>
                </h3>
            </div>
            <div class="col-sm-9 chapter-title">
                <?php if ($exhibitSection->hide_title !== 1): ?>
                <h2>
                    <?php echo (!empty($bgImgUrl))? '<span>' : ''; ?>
                    <?php echo htmlspecialchars(strip_tags($exhibitSection->title), ENT_QUOTES | ENT_HTML5); ?>
                    <?php echo (!empty($bgImgUrl))? '</span>' : ''; ?>
                </h2>
                <?php endif; ?>
            </div>
        </div>
    </div>
</<?php echo $sectionTag; ?>>