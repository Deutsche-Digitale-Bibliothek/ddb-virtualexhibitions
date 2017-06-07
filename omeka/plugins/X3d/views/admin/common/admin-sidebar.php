<div class="x3d-elements panel">
    <h4><?php echo __('3D-Dateien'); ?></h4>
    <div>
    <?php if (!$x3d): ?>
        <p><?php echo __('Es ist keine 3D-Dateien zugewiesen.'); ?></p>
    <?php else: ?>
        <?php $x3dDir = FILES_DIR . DIRECTORY_SEPARATOR . 'x3d' . DIRECTORY_SEPARATOR . $x3d->directory; ?>
        <?php $x3dWebdir = WEB_FILES . '/x3d/' . $x3d->directory; ?>
        <p>Aktuelle X3d Dateien</p>
        <ul>
            <?php if (file_exists($x3dDir . DIRECTORY_SEPARATOR . $x3d->x3d_file)): ?>
                <li>X3D Datei:
                    <a href="<?php echo $x3dWebdir . '/' . $x3d->x3d_file; ?>" target="_blank"><?php echo $x3d->x3d_file; ?></a>
                </li>
            <?php endif; ?>
            <?php if (file_exists($x3dDir . DIRECTORY_SEPARATOR . $x3d->texture_file)): ?>
                <li>Texturdatei:
                    <a href="<?php echo $x3dWebdir . '/' . $x3d->texture_file; ?>" target="_blank"><?php echo $x3d->texture_file; ?></a>
                </li>
            </tr>
            <?php endif; ?>
            <?php if (file_exists($x3dDir . DIRECTORY_SEPARATOR . 'or_' . $x3d->thumbnail)): ?>
                <li>Vorschaubild: <br>
                    <img src="<?php echo $x3dWebdir . '/sq_' . $x3d->thumbnail; ?>" alt="<?php echo __('Vorschaubild'); ?>" style="width:200px; height:auto;">
                </li>
            </tr>
            <?php endif; ?>
        </ul>
    <?php endif; ?>
    </div>
</div>

