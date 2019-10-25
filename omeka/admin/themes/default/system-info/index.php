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
    <tr><th colspan="2">PHP Konfiguration</th></tr>
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
<?php if (current_user()->role === 'super'): ?>
<h2>phpinfo</h2>
<?php
ob_start();
phpinfo();
$phpinfo = base64_encode(ob_get_clean());
?>
<iframe src="data:text/html;base64,<?php echo $phpinfo; ?>" style="width:100%;height:100vh;max-height:800px;border: 1px solid #999;"></iframe>
<?php endif; ?>
<?php fire_plugin_hook('admin_system_info', array('system_info' => $info, 'view' => $this)); ?>
<?php echo foot();
