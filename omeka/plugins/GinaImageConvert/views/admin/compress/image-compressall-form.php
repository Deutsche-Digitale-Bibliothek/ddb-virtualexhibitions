<fieldset>
    <legend><?php echo __('Einstellungen für alle Bilder'); ?></legend>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel('compressall_target', __('Ziel-Qualität')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Ziel-Qualität einstellen.') . '<br>'
                    . __('Orientierungshilfe (standard: 0.9999):') . '<br>'
                    . __('niedrig: 0.999 | mittel: 0.9999 | hoch: 0.99995 | sehr hoch: 0.99999'); ?>
            </p>
            <?php echo $this->formText('compressall_target', $params['compressall_target']); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel('compressall_min', __('Minimum JPEG Qualität')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Minimum JPEG Qualität einstellen.') . '<br>'
                    . __('Muss kleiner als der Maximalwert (s.u.) sein, Standard ist 40.'); ?>
            </p>
            <?php echo $this->formText('compressall_min', $params['compressall_min']); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel('compressall_max', __('Maximum JPEG Qualität')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Maximum JPEG Qualität einstellen.') . '<br>'
                    . __('Muss größer als der Minimalwert (s.o.) sein, Standard ist 95.'); ?>
            </p>
            <?php echo $this->formText('compressall_max', $params['compressall_max']); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel('compressall_loops', __('Anzahl der Versuchsläufe')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Anzahl der Versuchsläufe festlegen.') . '<br>'
                    . __('Der Wert sollte 10 nicht übersteigen, Standard ist 6.'); ?>
            </p>
            <?php echo $this->formText('compressall_loops', $params['compressall_loops']); ?>
        </div>
    </div>
</fieldset>