<?php
$subtitle = metadata('exhibit', 'subtitle');
$titlebackground = metadata('exhibit', 'titlebackground');
$titlebackgroundcolor = metadata('exhibit', 'titlebackgroundcolor');
$titlebgpos = metadata('exhibit', 'titlebgpos');
if (null === $titlebackgroundcolor) {
    $titlebackgroundcolor = key($colors);
}
if (null === $titlebgpos) {
    $titlebgpos = 'center center';
}
$titleimage = null;
if ($exhibitType === 'litfass_ddb') {
    $titleimage = metadata('exhibit', 'titleimage');
}
?>
<section
    data-color-palette="<?php echo $colorpalette; ?>"
    data-color-section="<?php echo $titlebackgroundcolor; ?>"
    class="section section-title <?php echo $colors[$titlebackgroundcolor]['type']; if ($titleimage): echo ' with-title-image'; endif;?> tile"
    <?php echo ($titlebackground)? 'style="background-image: url(\'' . WEB_FILES . '/layout/titlebackground/' . $titlebackground . '\'); background-position: ' . $titlebgpos . ';"' . "\n" : "\n"; ?>
    id="se<?php echo $sectionCounter; ?>">
    <?php if ($titleimage): ?>
        <img src="<?php echo WEB_FILES . '/layout/titleimage/' . $titleimage; ?>" alt="title" class="titleimage">
        <?php endif; ?>
    <div class="title-container">
        <div class="title-top">
            <div class="container-fluid">
                <h1><span><?php echo $title ?></span></h1>
                <?php if (!empty($subtitle)): ?><h2><span><?php echo $subtitle ?></span></h2><?php endif; ?>
            </div>
        </div>
        <div class="title-bottom">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-6">
                        <?php if(!empty($institutions)): ?>
                        <div class="credits">
                            <span>
                                <?php $institutionsHtml = ''; ?>
                                <?php foreach ($institutions as $institution): ?>
                                <?php
                                if (!empty($institution['name'])) {
                                    if (!empty($institutionsHtml)) { $institutionsHtml .= '<br>'; }
                                    $institutionsHtml .= $institution['name'];
                                }
                                ?>
                                <?php endforeach; ?>
                                <?php echo $institutionsHtml; ?>
                            </span>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-6">
                        <div class="next-page-link">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48"
                                class="icon-next-page title-page-next-link">
                            <g transform="translate(-276.5 -376.5)">
                                <g transform="translate(365.5 455.5) rotate(180)">
                                <g>
                                    <line class="cls-1" x2="10" transform="translate(52.5 32.5) rotate(180)" />
                                    <line class="cls-1" x2="10" transform="translate(52.5 77.5) rotate(180)" />
                                    <line class="cls-1" y1="45" transform="translate(42.5 77.5) rotate(180)" />
                                </g>
                                <g transform="translate(130 110) rotate(180)">
                                    <line class="cls-1" x2="10" transform="translate(52.5 32.5) rotate(180)" />
                                    <line class="cls-1" x2="10" transform="translate(52.5 77.5) rotate(180)" />
                                    <line class="cls-1" y1="45" transform="translate(42.5 77.5) rotate(180)" />
                                </g>
                                </g>
                                <path class="cls-2 arrow-bottom" d="M0,0,11.744,12.063,23.807,0" transform="translate(288.565 390.565)" />
                                <path class="cls-2 arrow-top" d="M0,0,11.744,12.063,23.807,0" transform="translate(288.565 400.565)" />
                            </g>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>