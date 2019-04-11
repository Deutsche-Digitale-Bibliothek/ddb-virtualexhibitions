<?php
// ------------- MAIN SINGLE PAGE TEMPLATE -------------
$title = metadata('exhibit', 'title');
$publishDate = get_option('publish-date');
if ($publishDate) {
    $publishDate = date('d.m.Y', strtotime($publishDate));
} else {
    $publishDate = date('d.m.Y');
}
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
    'type' => 'ddb-litfass-start',
    'menu_icon' => 'home'
];
$sectionCounter++;
// Sections
$chapterCounter = 0;
set_exhibit_pages_for_loop_by_exhibit();
foreach (loop('exhibit_page') as $exhibitSection):
    // reset currentAttechmentMediaType
    ExhibitDdbHelper::$currentAttechmentMediaType = 'text';
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
        'type' => $exhibitSection->layout,
        'menu_icon' => ExhibitDdbHelper::$currentAttechmentMediaType
    ];
    $sectionCounter++;
endforeach;
// apparatus
require  $dir . '/section-ddb-litfass-team.php';
$sectionAnchors = (empty($sectionAnchors))? '' : $sectionAnchors . ', ';
$sectionAnchors .= "'s" . $sectionCounter . "'";
$sectionColors = (empty($sectionColors))? '' : $sectionColors . ',';
$sectionColors .= 'litfassColorPalettes.base.white.hex';
$sectionTitles[] = [
    'title' => __('Team'),
    'pagethumbnail' => '',
    'type' => 'team',
    'menu_icon' => 'team'
];
$sectionCounter++;
require  $dir . '/section-ddb-litfass-impressum.php';
$sectionAnchors = (empty($sectionAnchors))? '' : $sectionAnchors . ', ';
$sectionAnchors .= "'s" . $sectionCounter . "'";
$sectionColors = (empty($sectionColors))? '' : $sectionColors . ',';
$sectionColors .= 'litfassColorPalettes.base.white.hex';
$sectionTitles[] = [
    'title' => __('Impressum'),
    'pagethumbnail' => '',
    'type' => 'legal',
    'menu_icon' => 'legal'
];
$sectionCounter++;
require  $dir . '/section-ddb-litfass-datenschutz.php';
$sectionAnchors = (empty($sectionAnchors))? '' : $sectionAnchors . ', ';
$sectionAnchors .= "'s" . $sectionCounter . "'";
$sectionColors = (empty($sectionColors))? '' : $sectionColors . ',';
$sectionColors .= 'litfassColorPalettes.base.white.hex';
$sectionTitles[] = [
    'title' => __('Dattenschutz'),
    'pagethumbnail' => '',
    'type' => 'privacy',
    'menu_icon' => 'privacy'
];
$sectionCounter++;
?>
</div>
<?php echo foot(compact('sectionAnchors', 'sectionColors', 'colorpalette'), 'spa_footer_scripts'); ?>
<?php require  $dir . '/summary_header.php'; ?>
<?php require  $dir . '/summary_menu.php'; ?>
<?php echo foot(NULL, 'spa_footer'); ?>
