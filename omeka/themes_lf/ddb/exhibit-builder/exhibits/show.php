<?php
$host  = $_SERVER['HTTP_HOST'] . WEB_DIR;
$pageParent = $exhibit_page->getParent();
$pageSiblings = ($pageParent ? exhibit_builder_child_pages($pageParent) : $exhibit->getTopPages());
$counter = 0;
$inSlider = false;
foreach ($pageSiblings as $key => $pageSibiling) {
    $pageoptions = unserialize($pageSibiling->pageoptions);
    if (isset($pageoptions['slider']) && $pageoptions['slider'] === 'start') {
      $inSlider = true;
      $counter++;
    }
    if (isset($pageoptions['slider']) && $pageoptions['slider'] === 'end') {
      $inSlider = false;
    }
    if ($inSlider === false && (!isset($pageoptions['slider']) || $pageoptions['slider'] !== 'end')) {
      $counter++;
    }
    if ($pageSibiling->id == $exhibit_page->id) {
        header('Location: ' . WEB_DIR . '/#s' . $counter, TRUE, 301);
    }
}
exit;
