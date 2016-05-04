<fieldset class="<?php echo html_escape($layout); ?>">
    <?php for($i=1; $i<=8; $i++): ?>
        <div class="section">
        <?php
            if (($i % 2) == 0) {
                echo exhibit_builder_layout_form_text($i);
                echo exhibit_builder_layout_form_item($i);
            } else {
                echo exhibit_builder_layout_form_item($i);
                echo exhibit_builder_layout_form_text($i);
            }
        ?>
        </div>
    <?php endfor; ?>
</fieldset>
