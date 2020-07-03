<?php
    $title = __('Bild rekomprimieren');
    echo head(array('title' => html_escape($title)));
    echo flash();
?>
<?php // echo $id; ?>
<?php // echo FILES_DIR; ?>
<?php // var_dump($dbFile); ?>
<div style="clear:both;margin-bottom:1rem;" class="clearfix">
    <div class="three columns alpha">
        <?php echo file_markup($dbFile, array('imageSize' => 'thumbnail')); ?>
    </div>
    <div class="seven columns omega">
        <div id="item-metadata" class="panel">
            <h4><?php echo __('Item'); ?></h4>
            <p><?php echo link_to_item(null, array(), 'show', $dbFile->getItem()); ?></p>
        </div>

        <div id="file-links" class="panel">
            <h4><?php echo __('Direct Links'); ?></h4>
            <ul>
                <?php if (isset($fileSizes['original'])): ?>
                <li>
                    <a target="_blank" rel="noopener"
                        href="<?php echo metadata($dbFile, 'uri'); ?>">
                        <?php echo __('Original'); ?>
                        <?php echo $fileSizes['original']; ?> KB
                    </a>
                </li>
                <?php endif; ?>
                <?php if (isset($fileSizes['original_compressed'])): ?>
                <li>
                    <a target="_blank" rel="noopener"
                        href="<?php echo WEB_FILES . '/original_compressed/' . $dbFile->filename; ?>">
                        <?php echo __('Original komprimiert'); ?>
                        <?php echo $fileSizes['original_compressed']; ?> KB
                        <?php if (isset($fileSizesOld['original_compressed'])): ?>
                        <?php if ($fileSizesOld['original_compressed'] === $fileSizes['original_compressed']): ?>
                        <span style="color: #666;">
                        <?php elseif ($fileSizesOld['original_compressed'] > $fileSizes['original_compressed']): ?>
                        <span style="color: #060;">
                        <?php else: ?>
                        <span style="color: #e00;">
                        <?php endif; ?>
                        (vorher <?php echo $fileSizesOld['original_compressed']; ?> KB)
                        </span>
                        <?php endif; ?>
                    </a>
                </li>
                <?php endif; ?>
                <?php if ($dbFile->has_derivative_image): ?>
                    <?php if (isset($fileSizes['fullsize'])): ?>
                    <li>
                        <a target="_blank" rel="noopener"
                            href="<?php echo metadata($dbFile, 'fullsize_uri'); ?>">
                            <?php echo __('Fullsize'); ?>
                            <?php echo $fileSizes['fullsize']; ?> KB
                            <?php if (isset($fileSizesOld['fullsize'])): ?>
                            <?php if ($fileSizesOld['fullsize'] === $fileSizes['fullsize']): ?>
                            <span style="color: #666;">
                            <?php elseif ($fileSizesOld['fullsize'] > $fileSizes['fullsize']): ?>
                            <span style="color: #060;">
                            <?php else: ?>
                            <span style="color: #e00;">
                            <?php endif; ?>
                            (vorher <?php echo $fileSizesOld['fullsize']; ?> KB)
                            </span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if (isset($fileSizes['middsize'])): ?>
                    <li>
                        <a target="_blank" rel="noopener"
                            href="<?php echo metadata($dbFile, 'middsize_uri'); ?>">
                            <?php echo __('Mittlere Größe'); ?>
                            <?php echo $fileSizes['middsize']; ?> KB
                            <?php if (isset($fileSizesOld['middsize'])): ?>
                            <?php if ($fileSizesOld['middsize'] === $fileSizes['middsize']): ?>
                            <span style="color: #666;">
                            <?php elseif ($fileSizesOld['middsize'] > $fileSizes['middsize']): ?>
                            <span style="color: #060;">
                            <?php else: ?>
                            <span style="color: #e00;">
                            <?php endif; ?>
                            (vorher <?php echo $fileSizesOld['middsize']; ?> KB)
                            </span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if (isset($fileSizes['thumbnails'])): ?>
                    <li>
                        <a target="_blank" rel="noopener"
                            href="<?php echo metadata($dbFile, 'thumbnail_uri'); ?>">
                            <?php echo __('Thumbnail'); ?>
                            <?php echo $fileSizes['thumbnails']; ?> KB
                            <?php if (isset($fileSizesOld['thumbnails'])): ?>
                            <?php if ($fileSizesOld['thumbnails'] === $fileSizes['thumbnails']): ?>
                            <span style="color: #666;">
                            <?php elseif ($fileSizesOld['thumbnails'] > $fileSizes['thumbnails']): ?>
                            <span style="color: #060;">
                            <?php else: ?>
                            <span style="color: #e00;">
                            <?php endif; ?>
                                (vorher <?php echo $fileSizesOld['thumbnails']; ?> KB)
                            </span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if (isset($fileSizes['square_thumbnails'])): ?>
                    <li>
                        <a target="_blank" rel="noopener"
                            href="<?php echo metadata($dbFile, 'square_thumbnail_uri'); ?>">
                            <?php echo __('Square Thumbnail'); ?>
                            <?php echo $fileSizes['square_thumbnails']; ?> KB
                            <?php if (isset($fileSizesOld['square_thumbnails'])): ?>
                            <?php if ($fileSizesOld['square_thumbnails'] === $fileSizes['square_thumbnails']): ?>
                            <span style="color: #666;">
                            <?php elseif ($fileSizesOld['square_thumbnails'] > $fileSizes['square_thumbnails']): ?>
                            <span style="color: #060;">
                            <?php else: ?>
                            <span style="color: #e00;">
                            <?php endif; ?>
                            (vorher <?php echo $fileSizesOld['square_thumbnails']; ?> KB)
                            </span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>
        </div>
        <?php if ($dbFile->mime_type === 'image/png'): ?>
        <div class="panel">
            <p>
                Bei dem Originalbild handelt es sich um eine PNG-Datei.
                Hier werden nur die JPEG-Derivate &bdquo;Volle Größe&ldquo;,
                &bdquo;Mittlere Größe&ldquo;, &bdquo;Vorschau&ldquo; und
                &bdquo;Quadratische Vorschau&ldquo; komprimiert. Diese werden
                aber in der Ausstellung nicht verwendet, da JPEG-Bilder über
                keine Alphatransparenz verfügen.
                Es wird stattdessen immer das Original verwendet.
            </p>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php if (isset($log) && !empty($log)): ?>
<?php // var_dump($log); ?>
<div style="clear:both;" class="clearfix">
    <h2>Erbebnisse der aktuellen Komprimierung</h2>
    <?php foreach($log['files'] as $filekey => $filelog): ?>
        <h4><?php echo $filekey; ?></h4>
        <table>
            <thead>
                <tr>
                    <th>Parameter</th>
                    <th>Zeit</th>
                    <th>Fehler</th>
                    <th>Kompression</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <table>
                            <tr>
                                <th>Ziel-Qualität</th>
                                <td><?php echo $log['params']['compress_' . $filekey . '_target']; ?></td>
                            </tr>
                            <tr>
                                <th>Minimum JPEG Qualität</th>
                                <td><?php echo $log['params']['compress_' . $filekey . '_min']; ?></td>
                            </tr>
                            <tr>
                                <th>Maximum JPEG Qualität</th>
                                <td><?php echo $log['params']['compress_' . $filekey . '_max']; ?></td>
                            </tr>
                            <tr>
                                <th>Anzahl der Versuchsläufe</th>
                                <td><?php echo $log['params']['compress_' . $filekey . '_loops']; ?></td>
                            </tr>
                            <tr>
                                <th>Verwendete Methode</th>
                                <td><?php echo $log['params']['compress_' . $filekey . '_method']; ?></td>
                            </tr>
                        </table>
                    </td>
                    <td><?php echo $filelog['time']; ?></td>
                    <td><?php echo $filelog['error']; ?></td>
                    <td>
                    <?php foreach ($filelog['compress'] as $co): ?>
                        <?php echo $co; ?><br>
                    <?php endforeach; ?>
                    </td>
                </tr>
            </tbody>
        </table>
    <?php endforeach; ?>
</div>
<?php endif; ?>
<div style="clear:both;" class="clearfix">
    <form id="gina-image-compressall-form" method="post" enctype="multipart/form-data">
        <div class="seven columns alpha">
            <?php echo common('image-compress-form', array('params' => $params), 'compress'); ?>
        </div>
        <div id="save" class="three columns omega">
            <?php echo $this->formSubmit('compress_submit', __('Bild komprimieren'), array('class'=>'submit big green button')); ?>
        </div>
    </form>
</div>
<?php echo foot(); ?>