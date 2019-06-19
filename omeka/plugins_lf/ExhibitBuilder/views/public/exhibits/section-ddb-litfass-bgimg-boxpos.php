<?php $attachment = exhibit_builder_page_attachment(1); ?>
<?php
if (!isset($pageoptions) || false === $pageoptions || !isset($pageoptions['boxpos'])) {
    $pageoptions['boxpos'] = 'mc';
}
if (!isset($pageoptions) || false === $pageoptions || !isset($pageoptions['bgpos'])) {
    $pageoptions['bgpos'] = 'mc';
}
// $bgImgUrl = ExhibitDdbHelper::getOriginalImageUrl($attachment);
$bgImgUrl = ExhibitDdbHelper::getFullsizeImageUrl($attachment);
?>
<<?php echo $sectionTag; ?>
    data-color-palette="<?php echo $colorpalette; ?>"
    data-color-section="<?php echo $exhibitSection->backgroundcolor; ?>"
    <?php echo ($inSlider)? 'data-slideno="' . $slideCounter . '"' : ''; ?>
    class="<?php echo ($inSlider)? 'slide' : 'section'; ?> section-pos tile"
    id="se<?php echo $sectionCounter; ?><?php echo ($inSlider)? '-slide' . $slideCounter : ''; ?>"
    <?php echo (!empty($bgImgUrl))? ' style="background-image: url(' . $bgImgUrl . '); background-position: ' . $pageoptions['bgpos'] . ';"' : ''; ?>>
    <div class="section-container-pos pos-box-<?php echo $pageoptions['boxpos'] ?>">
        <div class="pos-box">
            <?php if ($exhibitSection->hide_title !== 1): ?>
            <h3>
                <?php echo htmlspecialchars(strip_tags($exhibitSection->title), ENT_QUOTES | ENT_HTML5); ?>
            </h3>
            <?php endif; ?>
            <?php $pageText = exhibit_builder_page_text(1); ?>
            <?php if (empty($pageText)): ?>
            <h4><i>Bitte Text eingeben ...</i></h4>
            <?php else: ?>
            <?php echo $pageText; ?>
            <?php endif; ?>
        </div>
    </div>
</<?php echo $sectionTag; ?>>