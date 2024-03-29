<?php
$pageTitle = __('Dashboard');
echo head(array('bodyclass'=>'index primary-secondary', 'title'=>$pageTitle)); ?>

<?php
$total_items = total_records('Item');
$total_collections = total_records('Collection');
$total_tags = total_records('Tag');
$stats = array(
    array(link_to('items', null, $total_items), __(plural('item', 'items', $total_items))),
    array(link_to('collections', null, $total_collections), __(plural('collection', 'collections', $total_collections))),
    array(link_to('tags', null, $total_tags), __(plural('tag', 'tags', $total_tags)))
); ?>
<?php if (is_allowed('Plugins', 'edit')):
    $total_plugins = total_records('Plugin');
    $stats[] = array(link_to('plugins', null, $total_plugins), __(plural('plugin', 'plugins', $total_plugins)));
endif; ?>
<?php if (is_allowed('Users', 'edit')):
    $total_users = total_records('User');
    $stats[] = array(link_to('users', null, $total_users), __(plural('user', 'users', $total_users)));
endif; ?>
<?php if (is_allowed('Themes', 'edit')):
    $themeName = Theme::getTheme(Theme::getCurrentThemeName('public'))->title;
    $stats[] = array(link_to('themes', null, $themeName), __('theme'));
endif; ?>
<?php $stats = apply_filters('admin_dashboard_stats', $stats, array('view' => $this)); ?>

<?php // Retrieve the latest version of Omeka by pinging the Omeka server. ?>
<?php $userRole = current_user()->role; ?>
<!-- not this time  - Grandgeorg Websolutions: -->
<?php if (($userRole == 'super' || $userRole == 'admin') && false): ?>
<?php $latestVersion = latest_omeka_version(); ?>
      <?php if ($latestVersion and version_compare(OMEKA_VERSION, $latestVersion, '<')): ?>
            <div id="flash">
                <ul>
                    <li class="success"><?php echo __('A new version of Omeka is available for download.'); ?>
                    <a href="http://omeka.org/download/"><?php echo __('Upgrade to %s', $latestVersion); ?></a>
                    </li>
                </ul>
            </div>
      <?php endif; ?>
<?php endif; ?>
<!-- :not this time - Grandgeorg Websolutions -->

<section id="stats">
    <?php foreach ($stats as $statInfo): ?>
    <p><span class="number"><?php echo $statInfo[0]; ?></span><br><?php echo $statInfo[1]; ?></p>
    <?php endforeach; ?>
</section>

<?php $panels = array(); ?>

<?php ob_start(); ?>
<h2><?php echo __('Recent Items'); ?></h2>
<?php
    set_loop_records('items', get_recent_items(5));
    foreach (loop('items') as $item):
?>
    <div class="recent-row">
        <p class="recent"><?php echo link_to_item(); ?></p>
        <?php if (is_allowed($item, 'edit')): ?>
        <p class="dash-edit"><?php echo link_to_item(__('Edit'), array('class' => 'small blue button'), 'edit'); ?></p>
        <?php endif; ?>
    </div>
<?php endforeach; ?>
    <?php if (is_allowed('Items', 'add')): ?>
    <div class="add-new-link"><p><a class="add-new-item button small green" href="<?php echo html_escape(url('items/add')); ?>"><?php echo __('Add a new item'); ?></a></p></div>
    <?php endif; ?>
<?php $panels[] = ob_get_clean(); ?>

<?php $panels = apply_filters('admin_dashboard_panels', $panels, array('view' => $this)); ?>
<?php for ($i = 0; $i < count($panels); $i++): ?>
<section class="five columns <?php echo ($i & 1) ? 'omega' : 'alpha'; ?>">
    <div class="panel">
        <?php echo $panels[$i]; ?>
    </div>
</section>
<?php endfor; ?>

<?php fire_plugin_hook('admin_dashboard', array('view' => $this)); ?>

<?php echo foot(); ?>
