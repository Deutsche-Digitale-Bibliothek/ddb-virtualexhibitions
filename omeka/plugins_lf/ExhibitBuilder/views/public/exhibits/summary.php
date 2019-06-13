<?php
// ------------- MAIN SINGLE PAGE TEMPLATE -------------
$exhibitType = metadata('exhibit', 'exhibit_type');
if (!isset($exhibitType) || empty($exhibitType)) {
    $exhibitType = 'litfass';
}
$title = metadata('exhibit', 'title');
$shorttitle = metadata('exhibit', 'shorttitle');
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
$sectionTag = 'section';
echo head(compact('title', 'colors', 'exhibitType'), 'spa_header');
$institutions = ExhibitDdbHelper::getInstitutions(
    metadata('exhibit', 'institutions', ['no_filter' => true, 'no_escape' => true]));
?>
<div id="fullpage" class="fullpage">
<?php
// Title Section
require  $dir . '/section-ddb-litfass-start.php';
$sectionAnchors = ExhibitDdbHelper::setSectionAnchors($sectionAnchors, $sectionCounter);
$sectionColors = ExhibitDdbHelper::setSectionColors($sectionColors,
    'litfassColorPalettes.' . $colorpalette . '.' . $titlebackgroundcolor . '.hex');
$sectionTitles[] = [
    'title' => htmlspecialchars(strip_tags($title), ENT_QUOTES | ENT_HTML5),
    'pagethumbnail' => '',
    'type' => 'ddb-litfass-start',
    'menu_icon' => 'home'
];
$sectionCounter++;

// Sections
$chapterCounter = 0;
// Slider
$inSlider = false;
$slideCounter = 0;
set_exhibit_pages_for_loop_by_exhibit();
foreach (loop('exhibit_page') as $exhibitSection):
    // reset currentAttechmentMediaType
    ExhibitDdbHelper::$currentAttechmentMediaType = 'text';
    $pageoptions = unserialize($exhibitSection->pageoptions);
    // slider open
    if (isset($pageoptions['slider']) && $pageoptions['slider'] === 'start') {
        $inSlider = true;
        $slideCounter = 0;
        $sectionAnchors = ExhibitDdbHelper::setSectionAnchors($sectionAnchors, $sectionCounter);
        $sectionColors = ExhibitDdbHelper::setSectionColors($sectionColors,
            'litfassColorPalettes.' . $colorpalette . '.' . $exhibitSection->backgroundcolor . '.hex');
        ExhibitDdbHelper::$currentAttechmentMediaType = 'slider';
        $sectionTitles[] = [
            'title' => htmlspecialchars(strip_tags($exhibitSection->title), ENT_QUOTES | ENT_HTML5),
            'pagethumbnail' => $exhibitSection->pagethumbnail,
            'type' => $exhibitSection->layout,
            'menu_icon' => ExhibitDdbHelper::$currentAttechmentMediaType //@TODO: add slider icon
        ];
    }
    if (isset($pageoptions['slider']) && $pageoptions['slider'] === 'end') {
        $inSlider = false;
        $sectionCounter++;
    }
    // get the section
    require  $dir . '/section-' . $exhibitSection->layout  . '.php';

    // set values for nav
    if ($inSlider === false) {
        $sectionTag = 'section';
    } else {
        $sectionTag = 'div';
    }
    if ($inSlider === false && (!isset($pageoptions['slider']) || $pageoptions['slider'] !== 'end')) {
        $sectionAnchors = ExhibitDdbHelper::setSectionAnchors($sectionAnchors, $sectionCounter);
        $sectionColors = ExhibitDdbHelper::setSectionColors($sectionColors,
            'litfassColorPalettes.' . $colorpalette . '.' . $exhibitSection->backgroundcolor . '.hex');
        $sectionTitles[] = [
            'title' => htmlspecialchars(strip_tags($exhibitSection->title), ENT_QUOTES | ENT_HTML5),
            'pagethumbnail' => $exhibitSection->pagethumbnail,
            'type' => $exhibitSection->layout,
            'menu_icon' => ExhibitDdbHelper::$currentAttechmentMediaType
        ];
        $sectionCounter++;
    } else {
        $slideCounter++;
    }
endforeach;
// apparatus
require  $dir . '/section-ddb-litfass-team.php';
$sectionAnchors = ExhibitDdbHelper::setSectionAnchors($sectionAnchors, $sectionCounter);
$sectionColors = ExhibitDdbHelper::setSectionColors($sectionColors, 'litfassColorPalettes.base.white.hex');
$sectionTitles[] = [
    'title' => __('Team'),
    'pagethumbnail' => '',
    'type' => 'team',
    'menu_icon' => 'team'
];
$sectionCounter++;
require  $dir . '/section-ddb-litfass-impressum.php';
$sectionAnchors = ExhibitDdbHelper::setSectionAnchors($sectionAnchors, $sectionCounter);
$sectionColors = ExhibitDdbHelper::setSectionColors($sectionColors, 'litfassColorPalettes.base.white.hex');
$sectionTitles[] = [
    'title' => __('Impressum'),
    'pagethumbnail' => '',
    'type' => 'legal',
    'menu_icon' => 'legal'
];
$sectionCounter++;
// require  $dir . '/section-ddb-litfass-datenschutz.php';
// $sectionAnchors = ExhibitDdbHelper::setSectionAnchors($sectionAnchors, $sectionCounter);
// $sectionColors = ExhibitDdbHelper::setSectionColors($sectionColors, 'litfassColorPalettes.base.white.hex');
// $sectionTitles[] = [
//     'title' => __('Datenschutz'),
//     'pagethumbnail' => '',
//     'type' => 'privacy',
//     'menu_icon' => 'privacy'
// ];
// $sectionCounter++;
?>
</div>
<?php echo foot(compact('sectionAnchors', 'sectionColors', 'colorpalette'), 'spa_footer_scripts'); ?>
<?php require  $dir . '/summary_header.php'; ?>
<?php require  $dir . '/summary_menu.php'; ?>
<?php echo foot(NULL, 'spa_footer'); ?>
