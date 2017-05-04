<fieldset class="<?php echo html_escape($layout); ?>">

<div class="primary">
    <h3><?php echo 'Texte'; ?></h3>
    <h4>Linke Spalte</h4>
    <?php echo exhibit_builder_layout_form_text(1); ?>
    <h4>Rechte Spalte</h4>
    <?php echo exhibit_builder_layout_form_text(2); ?>
</div>
<div class="secondary gallery">
    <h3><?php echo 'Bilder'; ?></h3>
    <?php
        for($i=1;$i<=13;$i++):
            if (1 == $i) {
                echo '<h2>Hauptbild</h2>';
            }
            echo exhibit_builder_layout_form_item($i);
        endfor;
    ?>
</div>
<div class="primary">
    <h3><?php echo '1. Zwischenüberschrift für zweispaltigen Text'; ?></h3>
    <?php echo exhibit_builder_layout_form_text(3); ?>
    <h3><?php echo '1. Zweispaltiger Text'; ?></h3>
    <?php echo exhibit_builder_layout_form_text(4); ?>
</div>
<div class="primary">
    <h3><?php echo '2. Zwischenüberschrift für zweispaltigen Text'; ?></h3>
    <?php echo exhibit_builder_layout_form_text(5); ?>
    <h3><?php echo '2. Zweispaltiger Text'; ?></h3>
    <?php echo exhibit_builder_layout_form_text(6); ?>
</div>
</fieldset>
