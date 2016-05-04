<fieldset class="<?php echo html_escape($layout); ?>">

<div class="primary">
    <h3><?php echo 'Text'; ?></h3>
    <h4>Linke Spalte</h4>
    <?php echo exhibit_builder_layout_form_text(1); ?>
</div>
<div class="secondary gallery">
    <h3><?php echo 'Bilder'; ?></h3>
    <?php
        for($i=1;$i<=8;$i++):
            echo exhibit_builder_layout_form_item($i);
        endfor;
    ?>
</div>
<div class="primary">
    <h3><?php echo 'Text'; ?></h3>
    <h4>Rechte Spalte</h4>
    <?php echo exhibit_builder_layout_form_text(2); ?>
</div>
</fieldset>
