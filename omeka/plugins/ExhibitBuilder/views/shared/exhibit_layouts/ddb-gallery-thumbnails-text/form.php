<fieldset class="<?php echo html_escape($layout); ?>">

<div class="primary">
    <h3><?php echo 'Zweisplatiger Text oberhalb'; ?></h3>
    <?php echo exhibit_builder_layout_form_text(1); ?>
</div>
<div class="secondary gallery">
    <h3><?php echo 'Bilder'; ?></h3>
    <?php
        for($i=1;$i<=24;$i++):
            echo exhibit_builder_layout_form_item($i);
        endfor;
    ?>
</div>
<div class="primary">
    <h3><?php echo 'Zwischenüberschrift für zweispaltigen Text unterhalb'; ?></h3>
    <?php echo exhibit_builder_layout_form_text(2); ?>
    <h3><?php echo 'Zweispaltiger Text unterhalb'; ?></h3>
    <?php echo exhibit_builder_layout_form_text(3); ?>
</div>
</fieldset>
