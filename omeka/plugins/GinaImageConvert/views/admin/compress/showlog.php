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
    <?php foreach ($logfile['params'] as $type => $options): ?>
    <tr>
        <th><?php echo $type == 'original' ? 'original_compressed' : $type; ?></th>
        <td>
            <table>
                <tr>
                <?php $tempOption = ''; ?>
                <?php foreach ($options as $optionKey => $option): ?>
                <?php if (array_key_exists($optionKey, $showOptions)): ?>
                    <th><?php echo $showOptions[$optionKey] ?></th>
                    <?php $tempOption .= '<td>' . $option . '</td>'; ?>
                <?php endif; ?>
                <?php endforeach; ?>
                </tr>
                <tr>
                    <?php echo $tempOption; ?>
                </tr>
            </table>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<table>
    <thead>
        <tr>
            <th>Datei</th>
            <th>Kompression</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($logfile['files'] as $filename => $file): ?>
            <?php
            $currentId = false;
            foreach ($dbFiles as $dbFile) {
                if ($dbFile->filename === $filename) {
                    $currentId = $dbFile->id;
                    $currentDbFile = $dbFile;
                    // $currentMimeType = $dbFile->mime_type;
                    break;
                }
            }
            ?>
            <tr>
                <td style="text-align:center;">
                <img style="max-width:360px;max-height:360px;"
                        src="<?php echo WEB_FILES . '/thumbnails/' . pathinfo($filename, PATHINFO_FILENAME) . '.jpg'; ?>"
                        alt="<?php echo $filename; ?>">
                    <?php if ($currentId): ?>
                    <div class="panel" style="text-align:left;">
                        <h4><?php echo __('Item'); ?></h4>
                        <p><?php echo link_to_item(null, array(), 'show', $currentDbFile->getItem()); ?></p>
                    </div>
                    <div class="panel" style="text-align:left;">
                        <h4><?php echo __('Direct Links'); ?></h4>
                        <ul>
                            <li>
                                <a target="_blank" rel="noopener"
                                    href="<?php echo metadata($currentDbFile, 'uri'); ?>">
                                    <?php echo __('Original'); ?>
                                </a>
                            </li>
                            <li>
                                <a target="_blank" rel="noopener"
                                    href="<?php
                                    if ($currentDbFile->mime_type === 'image/png') {
                                        echo WEB_FILES . '/fullsize/' .
                                            pathinfo($currentDbFile->filename, PATHINFO_FILENAME) . '.webp';
                                    } else {
                                        echo WEB_FILES . '/original_compressed/' . $currentDbFile->filename;
                                    }
                                    ?>">
                                    <?php echo __('Original komprimiert'); ?>
                                </a>
                            </li>
                            <?php if ($currentDbFile->has_derivative_image): ?>
                                <li>
                                    <a target="_blank" rel="noopener" href="<?php
                                        if ($currentDbFile->mime_type === 'image/png') {
                                            echo WEB_FILES . '/fullsize/' .
                                                pathinfo($currentDbFile->filename, PATHINFO_FILENAME) . '.webp';
                                        } else {
                                            echo metadata($currentDbFile, 'fullsize_uri');
                                        }
                                        ?>">
                                        <?php echo __('Fullsize'); ?>
                                    </a>
                                </li>
                                <li>
                                    <a target="_blank" rel="noopener"
                                        href="<?php
                                        if ($currentDbFile->mime_type === 'image/png') {
                                            echo WEB_FILES . '/middsize/' .
                                                pathinfo($currentDbFile->filename, PATHINFO_FILENAME) . '.webp';
                                        } else {
                                            echo metadata($currentDbFile, 'middsize_uri');
                                        }
                                        ?>">
                                        <?php echo __('Mittlere Größe'); ?>
                                    </a>
                                </li>
                                <li>
                                    <a target="_blank" rel="noopener"
                                        href="<?php
                                        if ($currentDbFile->mime_type === 'image/png') {
                                            echo WEB_FILES . '/thumbnails/' .
                                                pathinfo($currentDbFile->filename, PATHINFO_FILENAME) . '.webp';
                                        } else {
                                            echo metadata($currentDbFile, 'thumbnail_uri');
                                        }
                                        ?>">
                                        <?php echo __('Thumbnail'); ?>
                                    </a>
                                </li>
                                <li>
                                    <a target="_blank" rel="noopener"
                                        href="<?php
                                        if ($currentDbFile->mime_type === 'image/png') {
                                            echo WEB_FILES . '/square_thumbnails/' .
                                                pathinfo($currentDbFile->filename, PATHINFO_FILENAME) . '.webp';
                                        } else {
                                            echo metadata($currentDbFile, 'square_thumbnail_uri');
                                        }
                                        ?>">
                                        <?php echo __('Square Thumbnail'); ?>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                        <p><a target="_blank" rel="noopener"
                            href="<?php echo url('gina-image-convert/compressfile/'
                            . $currentId); ?>">Einzelnes Bild komprimieren</a></p>
                    </div>
                    <?php else: ?>
                    Datei: <?php echo $filename; ?><br>
                    <?php endif; ?>
                </td>
                <td>
                <?php foreach ($file as $type => $compress): ?>
                    <h4><?php echo $sizeNames[$type]; ?></h4>
                    <p><small><?php echo $compress['time']; ?></small></p>
                    <p>Fehler: <?php echo $compress['error']; ?></p>
                    <p>
                    <?php foreach ($compress['compress'] as $co): ?>
                        <?php echo $co; ?><br>
                    <?php endforeach; ?>
                    </p>
                <?php endforeach; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php echo foot(); ?>