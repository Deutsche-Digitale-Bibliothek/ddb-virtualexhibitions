<fieldset>
    <legend><?php echo __('Einstellungen für Original - (original compressed)'); ?></legend>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel('compress_original_target', __('Ziel-Qualität')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Ziel-Qualität einstellen.') . '<br>'
                    . __('Orientierungshilfe (standard: 0.9999):') . '<br>'
                    . __('niedrig: 0.999 | mittel: 0.9999 | hoch: 0.99995 | sehr hoch: 0.99999'); ?>
            </p>
            <?php echo $this->formText('compress_original_target', $params['compress_original_target']); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel('compress_original_min', __('Minimum JPEG Qualität')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Minimum JPEG Qualität einstellen.') . '<br>'
                    . __('Muss kleiner als der Maximalwert (s.u.) sein, Standard ist 40.'); ?>
            </p>
            <?php echo $this->formText('compress_original_min', $params['compress_original_min']); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel('compress_original_max', __('Maximum JPEG Qualität')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Maximum JPEG Qualität einstellen.') . '<br>'
                    . __('Muss größer als der Minimalwert (s.o.) sein, Standard ist 95.'); ?>
            </p>
            <?php echo $this->formText('compress_original_max', $params['compress_original_max']); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel('compress_original_loops', __('Anzahl der Versuchsläufe')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Anzahl der Versuchsläufe festlegen.') . '<br>'
                    . __('Der Wert sollte 10 nicht übersteigen, Standard ist 6.'); ?>
            </p>
            <?php echo $this->formText('compress_original_loops', $params['compress_original_loops']); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel('compress_original_method', __('Methode')); ?>
        </div>
        <div class="five columns omega inputs">
            <div class="explanation">
                <?php echo __('Methode zur Vorhersage der wahrgenommenen Qualität')
                    . '<ul>'
                    . '<li>' . __('SSIM - Structural similarity') . '</li>'
                    . '<li>' . __('MS-SSIM - Multi-scale structural similarity') . '</li>'
                    . '</ul>';
                ?>
            </div>
            <?php echo $this->formSelect(
                'compress_original_method',             // name
                $params['compress_original_method'],    // value
                null,                                   // attribs
                array(
                    'ssim' => 'SSIM',
                    'ms-ssim' => 'MS-SSIM'
                )                                       // options
            ); ?>
        </div>
    </div>
</fieldset>

<fieldset>
    <legend><?php echo __('Einstellungen für Detailansicht - (fullsize)'); ?></legend>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel('compress_fullsize_target', __('Ziel-Qualität')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Ziel-Qualität einstellen.') . '<br>'
                    . __('Orientierungshilfe (standard: 0.9999):') . '<br>'
                    . __('niedrig: 0.999 | mittel: 0.9999 | hoch: 0.99995 | sehr hoch: 0.99999'); ?>
            </p>
            <?php echo $this->formText('compress_fullsize_target', $params['compress_fullsize_target']); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel('compress_fullsize_min', __('Minimum JPEG Qualität')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Minimum JPEG Qualität einstellen.') . '<br>'
                    . __('Muss kleiner als der Maximalwert (s.u.) sein, Standard ist 40.'); ?>
            </p>
            <?php echo $this->formText('compress_fullsize_min', $params['compress_fullsize_min']); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel('compress_fullsize_max', __('Maximum JPEG Qualität')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Maximum JPEG Qualität einstellen.') . '<br>'
                    . __('Muss größer als der Minimalwert (s.o.) sein, Standard ist 95.'); ?>
            </p>
            <?php echo $this->formText('compress_fullsize_max', $params['compress_fullsize_max']); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel('compress_fullsize_loops', __('Anzahl der Versuchsläufe')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Anzahl der Versuchsläufe festlegen.') . '<br>'
                    . __('Der Wert sollte 10 nicht übersteigen, Standard ist 6.'); ?>
            </p>
            <?php echo $this->formText('compress_fullsize_loops', $params['compress_fullsize_loops']); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel('compress_fullsize_method', __('Methode')); ?>
        </div>
        <div class="five columns omega inputs">
            <div class="explanation">
                <?php echo __('Methode zur Vorhersage der wahrgenommenen Qualität')
                    . '<ul>'
                    . '<li>' . __('SSIM - Structural similarity') . '</li>'
                    . '<li>' . __('MS-SSIM - Multi-scale structural similarity') . '</li>'
                    . '</ul>';
                ?>
            </div>
            <?php echo $this->formSelect(
                'compress_fullsize_method',             // name
                $params['compress_fullsize_method'],    // value
                null,                                   // attribs
                array(
                    'ssim' => 'SSIM',
                    'ms-ssim' => 'MS-SSIM'
                )                                       // options
            ); ?>
        </div>
    </div>
</fieldset>

<fieldset>
    <legend><?php echo __('Einstellungen für "Middlesize"'); ?></legend>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel('compress_middsize_target', __('Ziel-Qualität')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Ziel-Qualität einstellen.') . '<br>'
                    . __('Orientierungshilfe (standard: 0.9999):') . '<br>'
                    . __('niedrig: 0.999 | mittel: 0.9999 | hoch: 0.99995 | sehr hoch: 0.99999'); ?>
            </p>
            <?php echo $this->formText('compress_middsize_target', $params['compress_middsize_target']); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel('compress_middsize_min', __('Minimum JPEG Qualität')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Minimum JPEG Qualität einstellen.') . '<br>'
                    . __('Muss kleiner als der Maximalwert (s.u.) sein, Standard ist 40.'); ?>
            </p>
            <?php echo $this->formText('compress_middsize_min', $params['compress_middsize_min']); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel('compress_middsize_max', __('Maximum JPEG Qualität')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Maximum JPEG Qualität einstellen.') . '<br>'
                    . __('Muss größer als der Minimalwert (s.o.) sein, Standard ist 95.'); ?>
            </p>
            <?php echo $this->formText('compress_middsize_max', $params['compress_middsize_max']); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel('compress_middsize_loops', __('Anzahl der Versuchsläufe')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Anzahl der Versuchsläufe festlegen.') . '<br>'
                    . __('Der Wert sollte 10 nicht übersteigen, Standard ist 6.'); ?>
            </p>
            <?php echo $this->formText('compress_middsize_loops', $params['compress_middsize_loops']); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel('compress_middsize_method', __('Methode')); ?>
        </div>
        <div class="five columns omega inputs">
            <div class="explanation">
                <?php echo __('Methode zur Vorhersage der wahrgenommenen Qualität')
                    . '<ul>'
                    . '<li>' . __('SSIM - Structural similarity') . '</li>'
                    . '<li>' . __('MS-SSIM - Multi-scale structural similarity') . '</li>'
                    . '</ul>';
                ?>
            </div>
            <?php echo $this->formSelect(
                'compress_middsize_method',             // name
                $params['compress_middsize_method'],    // value
                null,                                   // attribs
                array(
                    'ssim' => 'SSIM',
                    'ms-ssim' => 'MS-SSIM'
                )                                       // options
            ); ?>
        </div>
    </div>
</fieldset>

<fieldset>
    <legend><?php echo __('Einstellungen für Vorschaubilder (thumbnails)'); ?></legend>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel('compress_thumbnails_target', __('Ziel-Qualität')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Ziel-Qualität einstellen.') . '<br>'
                    . __('Orientierungshilfe (standard: 0.9999):') . '<br>'
                    . __('niedrig: 0.999 | mittel: 0.9999 | hoch: 0.99995 | sehr hoch: 0.99999'); ?>
            </p>
            <?php echo $this->formText('compress_thumbnails_target', $params['compress_thumbnails_target']); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel('compress_thumbnails_min', __('Minimum JPEG Qualität')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Minimum JPEG Qualität einstellen.') . '<br>'
                    . __('Muss kleiner als der Maximalwert (s.u.) sein, Standard ist 40.'); ?>
            </p>
            <?php echo $this->formText('compress_thumbnails_min', $params['compress_thumbnails_min']); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel('compress_thumbnails_max', __('Maximum JPEG Qualität')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Maximum JPEG Qualität einstellen.') . '<br>'
                    . __('Muss größer als der Minimalwert (s.o.) sein, Standard ist 95.'); ?>
            </p>
            <?php echo $this->formText('compress_thumbnails_max', $params['compress_thumbnails_max']); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel('compress_thumbnails_loops', __('Anzahl der Versuchsläufe')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Anzahl der Versuchsläufe festlegen.') . '<br>'
                    . __('Der Wert sollte 10 nicht übersteigen, Standard ist 6.'); ?>
            </p>
            <?php echo $this->formText('compress_thumbnails_loops', $params['compress_thumbnails_loops']); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel('compress_thumbnails_method', __('Methode')); ?>
        </div>
        <div class="five columns omega inputs">
            <div class="explanation">
                <?php echo __('Methode zur Vorhersage der wahrgenommenen Qualität')
                    . '<ul>'
                    . '<li>' . __('SSIM - Structural similarity') . '</li>'
                    . '<li>' . __('MS-SSIM - Multi-scale structural similarity') . '</li>'
                    . '</ul>';
                ?>
            </div>
            <?php echo $this->formSelect(
                'compress_thumbnails_method',           // name
                $params['compress_thumbnails_method'],  // value
                null,                                   // attribs
                array(
                    'ssim' => 'SSIM',
                    'ms-ssim' => 'MS-SSIM'
                )                                       // options
            ); ?>
        </div>
    </div>
</fieldset>


<fieldset>
    <legend><?php echo __('Einstellungen für quadratische Vorschaubilder (square thumbnails)'); ?></legend>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel('compress_square_thumbnails_target', __('Ziel-Qualität')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Ziel-Qualität einstellen.') . '<br>'
                    . __('Orientierungshilfe (standard: 0.9999):') . '<br>'
                    . __('niedrig: 0.999 | mittel: 0.9999 | hoch: 0.99995 | sehr hoch: 0.99999'); ?>
            </p>
            <?php echo $this->formText('compress_square_thumbnails_target', $params['compress_square_thumbnails_target']); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel('compress_square_thumbnails_min', __('Minimum JPEG Qualität')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Minimum JPEG Qualität einstellen.') . '<br>'
                    . __('Muss kleiner als der Maximalwert (s.u.) sein, Standard ist 40.'); ?>
            </p>
            <?php echo $this->formText('compress_square_thumbnails_min', $params['compress_square_thumbnails_min']); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel('compress_square_thumbnails_max', __('Maximum JPEG Qualität')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Maximum JPEG Qualität einstellen.') . '<br>'
                    . __('Muss größer als der Minimalwert (s.o.) sein, Standard ist 95.'); ?>
            </p>
            <?php echo $this->formText('compress_square_thumbnails_max', $params['compress_square_thumbnails_max']); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel('compress_square_thumbnails_loops', __('Anzahl der Versuchsläufe')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Anzahl der Versuchsläufe festlegen.') . '<br>'
                    . __('Der Wert sollte 10 nicht übersteigen, Standard ist 6.'); ?>
            </p>
            <?php echo $this->formText('compress_square_thumbnails_loops', $params['compress_square_thumbnails_loops']); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel('compress_square_thumbnails_method', __('Methode')); ?>
        </div>
        <div class="five columns omega inputs">
            <div class="explanation">
                <?php echo __('Methode zur Vorhersage der wahrgenommenen Qualität')
                    . '<ul>'
                    . '<li>' . __('SSIM - Structural similarity') . '</li>'
                    . '<li>' . __('MS-SSIM - Multi-scale structural similarity') . '</li>'
                    . '</ul>';
                ?>
            </div>
            <?php echo $this->formSelect(
                'compress_square_thumbnails_method',            // name
                $params['compress_square_thumbnails_method'],   // value
                null,                                           // attribs
                array(
                    'ssim' => 'SSIM',
                    'ms-ssim' => 'MS-SSIM'
                )                                               // options
            ); ?>
        </div>
    </div>
</fieldset>