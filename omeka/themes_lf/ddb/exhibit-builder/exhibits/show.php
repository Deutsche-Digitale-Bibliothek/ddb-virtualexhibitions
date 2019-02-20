<?php
$host  = $_SERVER['HTTP_HOST'] . WEB_DIR;
$pageParent = $exhibit_page->getParent();
$pageSiblings = ($pageParent ? exhibit_builder_child_pages($pageParent) : $exhibit->getTopPages());
foreach ($pageSiblings as $key => $pageSibiling) {
    if ($pageSibiling->id == $exhibit_page->id) {
        // var_dump('Location: ' . WEB_DIR . '/#page' . ($key + 1));
        header('Location: ' . WEB_DIR . '/#page' . ($key + 1), TRUE, 301);
    }
}
exit;