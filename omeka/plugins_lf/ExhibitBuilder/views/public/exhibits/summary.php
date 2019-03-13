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
echo head(compact('title', 'colors'), 'spa_header');
$institutions = ExhibitDdbHelper::getInstitutions(
    metadata('exhibit', 'institutions', ['no_filter' => true, 'no_escape' => true]));
?>
<div id="fullpage" class="fullpage">
<?php
// Title Section
require  $dir . '/section-ddb-litfass-start.php';
$sectionAnchors = (empty($sectionAnchors))? '' : $sectionAnchors . ', ';
$sectionAnchors .= "'s" . $sectionCounter . "'";
$sectionColors = (empty($sectionColors))? '' : $sectionColors . ',';
$sectionColors .= 'litfassColorPalettes.' . $colorpalette . '.' . $titlebackgroundcolor . '.hex';
$sectionTitles[] = [
    'title' => htmlspecialchars(strip_tags($title), ENT_QUOTES | ENT_HTML5),
    'pagethumbnail' => '',
    'type' => 'ddb-litfass-start'
];
$sectionCounter++;
// Sections
$chapterCounter = 0;
set_exhibit_pages_for_loop_by_exhibit();
foreach (loop('exhibit_page') as $exhibitSection):
    // var_dump($exhibitSection);
    $pageoptions = unserialize($exhibitSection->pageoptions);
    // get the section
    require  $dir . '/section-' . $exhibitSection->layout  . '.php';
    // set values for nav
    $sectionAnchors = (empty($sectionAnchors))? '' : $sectionAnchors . ', ';
    $sectionAnchors .= "'s" . $sectionCounter . "'";
    $sectionColors = (empty($sectionColors))? '' : $sectionColors . ',';
    $sectionColors .= 'litfassColorPalettes.' . $colorpalette . '.' . $exhibitSection->backgroundcolor . '.hex';
    $sectionTitles[] = [
        'title' => htmlspecialchars(strip_tags($exhibitSection->title), ENT_QUOTES | ENT_HTML5),
        'pagethumbnail' => $exhibitSection->pagethumbnail,
        'type' => $exhibitSection->layout
    ];
    $sectionCounter++;
?>
<?php endforeach; ?>
</div>
<?php echo foot(compact('sectionAnchors', 'sectionColors', 'colorpalette'), 'spa_footer_scripts'); ?>
<?php require  $dir . '/summary_header.php'; ?>
<?php require  $dir . '/summary_menu.php'; ?>
<?php echo foot(NULL, 'spa_footer'); ?>

