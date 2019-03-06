<?php $attachment = exhibit_builder_page_attachment(1); ?>
<?php
if (!isset($pageoptions) || false === $pageoptions || !isset($pageoptions['boxpos'])) {
    $pageoptions['boxpos'] = 'mc';
}
$bgImgUrl = ExhibitDdbHelper::getOriginalImageUrl($attachment);
?>
<section
    data-color-palette="<?php echo $colorpalette; ?>"
    data-color-section="<?php echo $exhibitSection->backgroundcolor; ?>"
    class="section section-pos"
    id="se<?php echo $sectionCounter; ?>"
    <?php echo (!empty($bgImgUrl))? ' style="background-image: url(' . $bgImgUrl . ')"' : ''; ?>>
    <div class="section-container-pos pos-box-<?php echo $pageoptions['boxpos'] ?>">
        <div class="pos-box">
            <h3>
                <?php echo htmlspecialchars(strip_tags($exhibitSection->title), ENT_QUOTES | ENT_HTML5); ?>
            </h3>
            <?php $pageText = exhibit_builder_page_text(1); ?>
            <?php if (empty($pageText)): ?>
            <h4><i>Bitte Text eingeben ...</i></h4>
            <?php else: ?>
            <?php echo $pageText; ?>
            <?php endif; ?>
        </div>
    </div>
</section>