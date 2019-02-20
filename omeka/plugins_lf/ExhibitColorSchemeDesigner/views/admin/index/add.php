<?php
$head = array(
    'bodyclass' => 'exhibit-color-scheme-designer primary',
    'title' => html_escape(__('Farbschema hinzufügen'))
);
echo head($head);
?>
<?php echo flash(); ?>
<?php echo $form; ?>
<?php echo foot(); ?>
