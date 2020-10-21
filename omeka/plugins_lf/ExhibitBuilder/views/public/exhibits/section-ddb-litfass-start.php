<?php
$subtitle = metadata('exhibit', 'subtitle');
$titlebackground = metadata('exhibit', 'titlebackground');
if ($titlebackground) {
    $titlebackgroundExt = strtolower(pathinfo(FILES_DIR . '/layout/titlebackground/' . $titlebackground, PATHINFO_EXTENSION));
    $titlebackgroundFilename = strtolower(pathinfo(FILES_DIR . '/layout/titlebackground/' . $titlebackground, PATHINFO_FILENAME));
    if (($titlebackgroundExt === 'jpg' || $titlebackgroundExt === 'jpeg') &&
        is_file(FILES_DIR . '/layout/titlebackground/compressed/' . $titlebackground))
    {
        $titlebackgroundUrl = WEB_FILES . '/layout/titlebackground/compressed/' . $titlebackground;
    } elseif ($titlebackgroundExt === 'png' &&
        is_file(FILES_DIR . '/layout/titlebackground/compressed/' . $titlebackgroundFilename . '.webp'))
    {
        $titlebackgroundUrl = WEB_FILES . '/layout/titlebackground/compressed/' . $titlebackgroundFilename . '.webp';
    } else {
        $titlebackgroundUrl = WEB_FILES . '/layout/titlebackground/' . $titlebackground;
    }
}

$titlebackgroundcolor = metadata('exhibit', 'titlebackgroundcolor');
$titlebgpos = metadata('exhibit', 'titlebgpos');
if (null === $titlebackgroundcolor) {
    $titlebackgroundcolor = key($colors);
}
if (null === $titlebgpos) {
    $titlebgpos = 'center center';
}
$titleimage = null;
$titlelogo = null;
$usetitlelogo = false;
if ($exhibitType === 'litfass_ddb') {
    $titleimage = metadata('exhibit', 'titleimage');
    $titlelogo = metadata('exhibit', 'titlelogo');
    if ((!isset($titleimage) || empty($titleimage)) && isset($titlelogo) && !empty($titlelogo)) {
        $usetitlelogo = true;
    }
}
?>
<section
    data-color-palette="<?php echo $colorpalette; ?>"
    data-color-section="<?php echo $titlebackgroundcolor; ?>"
    class="section section-title <?php echo $colors[$titlebackgroundcolor]['type']; if ($titleimage): echo ' with-title-image'; endif; ?> tile"
    <?php echo ($titlebackground) ? 'style="background-image: url(\'' . $titlebackgroundUrl . '\'); background-position: ' . $titlebgpos . ';"' . "\n" : "\n"; ?>
    id="se<?php echo $sectionCounter; ?>">
<?php
if ($usetitlelogo) {
    require  $dir . '/partial-ddb-litfass-start-titlelogo.php';
} else {
    require  $dir . '/partial-ddb-litfass-start-default.php';
}
?>
</section>