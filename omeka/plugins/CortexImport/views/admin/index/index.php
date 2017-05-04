<?php
    echo head(array('title' => 'Cortex Import', 'bodyclass' => 'primary', 'content_class' => 'horizontal-nav'));
?>
<div id="primary">
    <h2><?php echo __('Step 1: Select URL and Item ID'); ?></h2>
    <?php echo flash(); ?>
    <?php echo $this->form; ?>
    <div><p style="clear:both;"><small>Standard-URL: <span style="font-family:"Courier New", Courier, monospace;">https://api.deutsche-digitale-bibliothek.de</span><br>
    Standard SPI Schlüssel: <span style="font-family:"Courier New", Courier, monospace;">gfiqa8TR74yeSsVA5GWGJU4LnLCw1iGnqmjAl8s8msB5wuJMTd31387184309122</span></p></div>
</div>
<?php
    echo foot();
?>
