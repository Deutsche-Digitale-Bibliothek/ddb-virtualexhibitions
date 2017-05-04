<p><?php echo __('Sie können hier dem Objekt ein X3D Element zuweisen oder das zugewiesene Element ändern.'); ?></p>
<?php if (isset($x3d)):
    $x3dDir = FILES_DIR . DIRECTORY_SEPARATOR . 'x3d' . DIRECTORY_SEPARATOR . $x3d->directory;
    $x3dWebdir = WEB_FILES . '/x3d/' . $x3d->directory;
?>
<h4>Aktuelle X3d Dateien</h4>
<table>
    <?php if (file_exists($x3dDir . DIRECTORY_SEPARATOR . $x3d->x3d_file)): ?>
    <tr>
        <td>X3D Datei</td>
        <td>
            <a href="<?php echo $x3dWebdir . '/' . $x3d->x3d_file; ?>" target="_blank"><?php echo $x3d->x3d_file; ?></a>
        </td>
    </tr>
    <?php endif; ?>
    <?php if (file_exists($x3dDir . DIRECTORY_SEPARATOR . $x3d->texture_file)): ?>
    <tr>
        <td>Texturdatei</td>
        <td>
            <a href="<?php echo $x3dWebdir . '/' . $x3d->texture_file; ?>" target="_blank"><?php echo $x3d->texture_file; ?></a>
        </td>
    </tr>
    <?php endif; ?>
    <?php if (file_exists($x3dDir . DIRECTORY_SEPARATOR . 'or_' . $x3d->thumbnail)): ?>
    <tr>
        <td>Vorschaubild</td>
        <td>
            <img src="<?php echo $x3dWebdir . '/sq_' . $x3d->thumbnail; ?>" alt="<?php echo __('Vorschaubild'); ?>" style="width:200px; height:auto;">
        </td>
    </tr>
    <?php endif; ?>
</table>
<div style="clear:both;">
    <div class="field two columns alpha" id="x3d-del-current">
        <label><?php echo __('Aktuelle X3D dateien löschen'); ?></label>
    </div>
    <div class="inputs five columns omega">
        <input name="x3d_delete" type="checkbox" value="1">&nbsp;Dateien und Datenbankeintrag für das Objekt werden entfernt.
    </div>
</div>
<?php endif; ?>
<div style="clear:both;">
        <div class="add-new"><?php echo __('X3D Dateien Hochladen'); ?></div>
        <p><?php echo __('Das 3D Objekt besteht aus der x3d-Datei und der Textturdatei (i.d.R. eine jpg-Datei). Laden Sie zusätzlich ein Vorschaubild hoch, um das Objekt in Übersichten repräsentieren zu können.'); ?></p>
        <div class="x3d-drawer-contents">
            <p><?php echo __('The maximum file size is %s.', max_file_size()); ?></p>

            <div class="field two columns alpha" id="x3d-file-inputs">
                <label><?php echo __('X3D Datei auswählen (.x3d)'); ?></label>
            </div>
            <div class="files four columns omega">
                <input name="x3d_file" type="file">
            </div>
            <div class="field two columns alpha" id="x3d-tuxture-file-inputs">
                <label><?php echo __('Textur-Datei auswählen (.jpg)'); ?></label>
            </div>
            <div class="files four columns omega">
                <input name="x3d_texture_file" type="file">
            </div>
            <div class="field two columns alpha" id="x3d-thn-file-inputs">
                <label><?php echo __('Vorschaubild auswählen (.jpg)'); ?></label>
            </div>
            <div class="files four columns omega">
                <input name="x3d_thn_file" type="file">
            </div>
        </div>
</div>