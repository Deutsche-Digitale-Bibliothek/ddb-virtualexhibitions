<?php
// ------------- MAIN SINGLE PAGE TEMPLATE -------------
$title = metadata('exhibit', 'title');
$colorpalette = metadata('exhibit', 'colorpalette');
$colors = ExhibitDdbHelper::getColorsFromExhibitColorPalette($colorpalette);
$dir = dirname(__FILE__);
$sectionCounter = 0;
$sectionAnchors = '';
$sectionColors = '';
$sectionTitles = array();
echo head(array('title' => $title), 'spa_header');
?>
<div id="fullpage" class="fullpage">
<?php
// Title Section
require  $dir . '/section-ddb-litfass-start.php';
$sectionAnchors = (empty($sectionAnchors))? '' : $sectionAnchors . ', ';
$sectionAnchors .= "'s" . $sectionCounter . "'";
$sectionColors = (empty($sectionColors))? '' : $sectionColors . ',';
$sectionColors .= 'litfassColorPalettes.' . $colorpalette . '.' . $titlebackgroundcolor . '.hex';
$sectionTitles[] = htmlspecialchars(strip_tags($title), ENT_QUOTES | ENT_HTML5);
$sectionCounter++;
// Sections
set_exhibit_pages_for_loop_by_exhibit();
foreach (loop('exhibit_page') as $exhibitSection):
    require  $dir . '/section-' . $exhibitSection->layout  . '.php';
?>

<?php
$sectionAnchors = (empty($sectionAnchors))? '' : $sectionAnchors . ', ';
$sectionAnchors .= "'s" . $sectionCounter . "'";
$sectionColors = (empty($sectionColors))? '' : $sectionColors . ',';
$sectionColors .= 'litfassColorPalettes.' . $colorpalette . '.' . $exhibitSection->backgroundcolor . '.hex';
$sectionTitles[] = htmlspecialchars(strip_tags($exhibitSection->title), ENT_QUOTES | ENT_HTML5);
$sectionCounter++;
?>
<?php endforeach; ?>
</div>
<?php echo foot(compact('sectionAnchors', 'sectionColors'), 'spa_footer_scripts'); ?>
<?php require  $dir . '/summary_header.php'; ?>
<?php require  $dir . '/summary_menu.php'; ?>
<?php echo foot(null, 'spa_footer'); ?>
