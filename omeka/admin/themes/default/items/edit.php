<?php
$itemTitle = metadata('item', 'display_title');
if ($itemTitle != '' && $itemTitle != __('[Untitled]')) {
    $itemTitle = ': ' . $itemTitle . ' ';
} else {
    $itemTitle = '';
}
$itemTitle = __('Edit Item #%s', metadata('item', 'id')) . $itemTitle;

echo head(array('title'=> $itemTitle, 'bodyclass'=>'items edit'));
include 'form-tabs.php';
echo flash();
?>

<form method="post" enctype="multipart/form-data" id="item-form" action="">
    <?php include 'form.php'; ?>
    <section class="three columns omega">
        <div id="save" class="panel">
            <?php echo $this->formSubmit('submit', __('Save Changes'), array('id'=>'save-changes', 'class'=>'submit big green button')); ?>
            <a href="<?php echo html_escape(public_url('items/show/'.metadata('item', 'id'))); ?>" class="big blue button" target="_blank"><?php echo __('View Public Page'); ?></a>
            <?php if (is_allowed($item, 'delete')): ?>
            <?php echo link_to_item(__('Delete'), array('class' => 'delete-confirm big red button'), 'delete-confirm'); ?>
            <?php endif; ?>

            <?php fire_plugin_hook("admin_items_panel_buttons", array('view'=>$this, 'record'=>$item)); ?>

            <div id="public-featured">
                <?php $currentuser = Zend_Registry::get('bootstrap')->getResource('currentuser'); ?>
                <?php if ($currentuser->role === 'super'): ?>
                    <div class="public">
                        <label for="public"><?php echo __('Public'); ?>:</label>
                        <?php echo $this->formCheckbox('public', $item->public, array(), array('1', '0')); ?>
                    </div>
                <?php elseif ( is_allowed('Items', 'makePublic') ): ?>
                    <?php echo $this->formHidden('public', $item->public); ?>
                    <?php if ($item->public != 1): ?>
                        <span class="title"><?php echo __('(Private)');  ?></span>
                    <?php endif; ?>
                <?php endif; ?>
            </div> <!-- end public-featured  div -->
            <!-- end collection-form div -->
            <?php fire_plugin_hook("admin_items_panel_fields", array('view'=>$this, 'record'=>$item)); ?>
        </div>
    </section>
</form>
<?php echo foot();?>
