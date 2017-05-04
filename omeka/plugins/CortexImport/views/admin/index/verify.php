<?php 
    echo head(array('title' => 'Cortex Import', 'bodyclass' => 'primary', 
        'content_class' => 'horizontal-nav'));
?>
<div id="primary">
    <h2><?php echo __('Step 2: Verify imported item information.'); ?></h2>
    <?php echo flash(); ?>
    <?php echo $this->form; ?>
</div>
<?php 
    echo foot(); 
?>
