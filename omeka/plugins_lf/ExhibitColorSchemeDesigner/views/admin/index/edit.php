<?php
$head = array('bodyclass' => 'exhibit-color-scheme-designer primary',
              'title' => __('Farbschema Gestalter für Ausstellungen | Bearbeiten "%s"', metadata('exhibit_color_scheme', 'name')));
echo head($head);
?>
<?php echo flash(); ?>
<p><?php echo __('Dieses Schema wurde erzeugt von <strong>%1$s</strong> am %2$s Uhr.<br>Es wurde zuletzt bearbeitet von <strong>%3$s</strong> am %4$s Uhr.',
    metadata('exhibit_color_scheme', 'created_username'),
    html_escape(format_date(metadata('exhibit_color_scheme', 'inserted'), Zend_Date::DATETIME_SHORT)),
    metadata('exhibit_color_scheme', 'modified_username'),
    html_escape(format_date(metadata('exhibit_color_scheme', 'updated'), Zend_Date::DATETIME_SHORT))); ?></p>
<?php echo $form; ?>
<?php echo foot(); ?>
