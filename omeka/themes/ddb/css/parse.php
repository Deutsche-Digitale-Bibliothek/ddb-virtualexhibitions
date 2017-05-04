<?php
$lines = file(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'ddb.css');
foreach ($lines as $line) {
    preg_match('/\(.*\.png/', $line, $matches);
    // var_dump($matches);
    if (!empty($matches)) {
        if ($matches{0}{1} == '"' || $matches{0}{1} == "'") {
            $subpos = 2;
        } else {
            $subpos = 1;
        }
        echo '<a href="https://www.deutsche-digitale-bibliothek.de/appStatic/' . substr($matches[0], $subpos) . '">' . substr($matches[0], $subpos) . '</a><br>';
    }
}
?>