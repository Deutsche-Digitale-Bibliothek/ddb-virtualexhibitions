<?php 
    echo head(array('title' => 'Cortex Import', 'bodyclass' => 'primary', 
        'content_class' => 'horizontal-nav'));
?>
<div id="primary">
    <h2><?php echo __('Step 3: Import item into Omeka.'); ?></h2>
    <?php echo flash(); ?>
    <?php echo $this->form; ?>
</div>
<?php 
    echo foot(); 
?>
