<?php
$head = array(
    'bodyclass' => 'exhibit-color-scheme-designer primary',
    'title' => html_escape(__('Farbschema Gestalter für Ausstellungen | Übersicht')),
    'content_class' => 'horizontal-nav'
);
echo head($head);
echo flash();
?>
<!--
<a class="add-colorscheme button small green" href="<?php echo html_escape(url('exhibit-color-scheme-designer/index/add')); ?>">
    <?php echo __('Farbschema hinzufügen'); ?>
</a>
-->

<?php // if (!has_loop_records('exhibit_color_scheme')): ?>
    <!-- <p><?php // echo __('Es sind keine Farbschemata definiert.'); ?> -->
    <!-- <a href="<?php echo html_escape(url('exhibit-color-scheme-designer/index/add')); ?>"><?php echo __('Farbschema hinzufügen'); ?></a></p> -->
<?php // else: ?>
    <?php // echo $this->partial('index/browse-list.php', array('simplePages' => $exhibit_color_schemes)); ?>
    <?php // if (isset($_GET['view']) && $_GET['view'] == 'hierarchy'): ?>
        <?php // echo $this->partial('index/browse-hierarchy.php', array('simplePages' => $exhibit_color_schemes)); ?>
    <?php // else: ?>
    <?php // endif; ?>
<?php // endif; ?>
<h2>Plugin ist derzeit noch in Vorbereitung.</h2>
<?php echo foot(); ?>
