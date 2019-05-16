<?php if ($inSlider) : ?>
<section
    data-color-palette="<?php echo $colorpalette; ?>"
    data-color-section="<?php echo $exhibitSection->backgroundcolor; ?>"
    class="section section-slides <?php echo 'slider-color-' . $colors[$exhibitSection->backgroundcolor]['type']; ?>"
    id="se<?php echo $sectionCounter; ?>">
<?php else: ?>
</section>
<?php endif; ?>