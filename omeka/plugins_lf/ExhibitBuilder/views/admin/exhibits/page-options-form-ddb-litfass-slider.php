<?php
$pageoptions = unserialize($exhibit_page->pageoptions);
if (isset($pageoptions['slider'])) {
    if ($pageoptions['slider'] === 'start') {
        echo '<h3>Anfang eines Sliders ⬇</h3>';
    } elseif ($pageoptions['slider'] === 'end') {
        echo '<h3>Ende eines Sliders ⬆</h3>';
    }
}
?>