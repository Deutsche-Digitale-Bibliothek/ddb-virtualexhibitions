<?php
$title = __('System Information');
echo head(array('title' => $title, 'bodyclass' => 'system-info')); ?>

<?php echo flash(); ?>
<table>
<?php foreach ($info as $category => $entries): ?>
    <tr><th colspan="2"><?php echo html_escape($category); ?></th></tr>
    <?php foreach ($entries as $name => $value): ?>
    <tr>
        <td><?php echo html_escape($name); ?></td>
        <td><?php echo html_escape($value); ?></td>
    </tr>
    <?php endforeach; ?>
<?php endforeach; ?>
    <tr><th colspan="2">PHP Kofiguration</th></tr>
    <tr>
        <td>upload_max_filesize</td>
        <td><?php echo ini_get('upload_max_filesize'); ?></td>
    </tr>
    <tr>
        <td>post_max_size</td>
        <td><?php echo ini_get('post_max_size'); ?></td>
    </tr>
    <tr>
        <td>max_execution_time</td>
        <td><?php echo ini_get('max_execution_time'); ?></td>
    </tr>
    <tr>
        <td>memory_limit</td>
        <td><?php echo ini_get('memory_limit'); ?></td>
    </tr>
</table>
<?php fire_plugin_hook('admin_system_info', array('system_info' => $info, 'view' => $this)); ?>
<?php echo foot();
