<?php
    $title = __('Letzte Bildkomprimierung auswerten');
    echo head(array('title' => html_escape($title)));
    echo flash();
?>
<?php // var_dump($logfile); ?>
<table>
    <tr>
        <th>Startzeit</th>
        <td><?php echo $logfile['start']; ?></td>
    </tr>
    <tr>
        <th>Endzeit</th>
        <td><?php echo $logfile['end']; ?></td>
    </tr>
    <tr>
        <th>Ziel-Qualität</th>
        <td><?php echo $logfile['params']['compressall_target']; ?></td>
    </tr>
    <tr>
        <th>Minimum JPEG Qualität</th>
        <td><?php echo $logfile['params']['compressall_min']; ?></td>
    </tr>
    <tr>
        <th>Maximum JPEG Qualität</th>
        <td><?php echo $logfile['params']['compressall_max']; ?></td>
    </tr>
    <tr>
        <th>Anzahl der Versuchsläufe</th>
        <td><?php echo $logfile['params']['compressall_loops']; ?></td>
    </tr>
</table>
<table>
    <thead>
        <tr>
            <th>Datei</th>
            <th>Zeit</th>
            <th>Fehler</th>
            <th>Kompression</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($logfile['files'] as $file): ?>
            <?php
            $currentId = false;
            $currenItemId = false;
            foreach ($dbFiles as $dbFile) {
                if ($dbFile->filename === $file['file']) {
                    $currentId = $dbFile->id;
                    $currenItemId = $dbFile->item_id;
                    break;
                }
            }
            ?>
            <tr>
                <td style="text-align:center;">
                    <?php if ($currentId): ?>
                    <ul style="text-align:left;">
                        <li>
                            <a href="<?php echo url('files/show/' . $currentId); ?>">Datei: <?php echo $file['file']; ?></a><br>
                        </li>
                        <li>
                            <a href="<?php echo url('items/show/' . $currenItemId); ?>">Objekt</a><br>
                        </li>
                    </ul>
                    <?php else: ?>
                    Datei: <?php echo $file['file']; ?><br>
                    <?php endif; ?>
                    <img style="max-width:120px;max-height:120px;"
                        src="<?php echo WEB_FILES . '/thumbnails/' . $file['file']; ?>"
                        alt="<?php echo $file['file']; ?>">
                </td>
                <td><?php echo $file['time']; ?></td>
                <td><?php echo $file['error']; ?></td>
                <td>
                <?php foreach ($file['compress'] as $co): ?>
                    <?php echo $co; ?><br>
                <?php endforeach; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php echo foot(); ?>