<?php
$attachment = exhibit_builder_page_attachment(1);
$bgImgUrl = ExhibitDdbHelper::getOriginalImageUrl($attachment);
$chapterCounter++;
?>
<section
    data-color-palette="<?php echo $colorpalette; ?>"
    data-color-section="<?php echo $exhibitSection->backgroundcolor; ?>"
    class="section section-chapter"
    id="se<?php echo $sectionCounter; ?>"
    <?php echo (!empty($bgImgUrl))? ' style="background-image: url(' . $bgImgUrl . ')"' : ''; ?>>
    <div class="section-container container-fluid">
        <div class="row auto">
            <div class="col-sm-3 chapter-num">
                <h3>
                    <span><?php echo ExhibitDdbHelper::getLeadingZeroNum($chapterCounter); ?></span>
                </h3>
            </div>
            <div class="col-sm-9 chapter-title">
                <h2>
                    <span><?php echo htmlspecialchars(strip_tags($exhibitSection->title), ENT_QUOTES | ENT_HTML5); ?></span>
                </h2>
            </div>
        </div>
    </div>
</section>