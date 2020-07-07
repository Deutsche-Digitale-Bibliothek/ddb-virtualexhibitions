<!-- original (compressed) -->
<fieldset>
    <legend><?php echo __('Einstellungen für Original - (original compressed)'); ?></legend>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel(
                'compress[original][recompress_target]',
                __('Ziel-Qualität')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Ziel-Qualität einstellen.') . '<br>'
                    . __('Orientierungshilfe (standard: 0.9999):') . '<br>'
                    . __('niedrig: 0.999 | mittel: 0.9999 | hoch: 0.99995 | sehr hoch: 0.99999'); ?>
            </p>
            <?php echo $this->formText(
                'compress[original][recompress_target]',
                $params['compress']['original']['recompress_target'],
                array('required' => 'required')); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel(
                'compress[original][recompress_min]',
                __('Minimum JPEG Qualität')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Minimum JPEG Qualität einstellen.') . '<br>'
                    . __('Muss kleiner als der Maximalwert (s.u.) sein, Standard ist 40.'); ?>
            </p>
            <?php echo $this->formText(
                'compress[original][recompress_min]',
                $params['compress']['original']['recompress_min'],
                array('required' => 'required')); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel(
                'compress[original][recompress_max]',
                __('Maximum JPEG Qualität')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Maximum JPEG Qualität einstellen.') . '<br>'
                    . __('Muss größer als der Minimalwert (s.o.) sein, Standard ist 95.'); ?>
            </p>
            <?php echo $this->formText(
                'compress[original][recompress_max]',
                $params['compress']['original']['recompress_max'],
                array('required' => 'required')); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel(
                'compress[original][recompress_loops]',
                __('Anzahl der Versuchsläufe')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Anzahl der Versuchsläufe festlegen.') . '<br>'
                    . __('Der Wert sollte 10 nicht übersteigen, Standard ist 6.'); ?>
            </p>
            <?php echo $this->formText(
                'compress[original][recompress_loops]',
                $params['compress']['original']['recompress_loops'],
                array('required' => 'required')); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel(
                'compress[original][recompress_method]',
                __('Methode')); ?>
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
                'compress[original][recompress_method]',              // name
                $params['compress']['original']['recompress_method'], // value
                null,                                                           // attribs
                array(
                    'ssim' => 'SSIM',
                    'ms-ssim' => 'MS-SSIM'
                )                                                               // options
            ); ?>
        </div>
    </div>
</fieldset>

<!-- fullsize -->
<fieldset>
    <legend><?php echo __('Einstellungen für Detailansicht - (fullsize)'); ?></legend>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel(
                'compress[fullsize][recompress_target]',
                __('Ziel-Qualität')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Ziel-Qualität einstellen.') . '<br>'
                    . __('Orientierungshilfe (standard: 0.9999):') . '<br>'
                    . __('niedrig: 0.999 | mittel: 0.9999 | hoch: 0.99995 | sehr hoch: 0.99999'); ?>
            </p>
            <?php echo $this->formText(
                'compress[fullsize][recompress_target]',
                $params['compress']['fullsize']['recompress_target'],
                array('required' => 'required')); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel(
                'compress[fullsize][recompress_min]',
                __('Minimum JPEG Qualität')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Minimum JPEG Qualität einstellen.') . '<br>'
                    . __('Muss kleiner als der Maximalwert (s.u.) sein, Standard ist 40.'); ?>
            </p>
            <?php echo $this->formText(
                'compress[fullsize][recompress_min]',
                $params['compress']['fullsize']['recompress_min'],
                array('required' => 'required')); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel(
                'compress[fullsize][recompress_max]',
                __('Maximum JPEG Qualität')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Maximum JPEG Qualität einstellen.') . '<br>'
                    . __('Muss größer als der Minimalwert (s.o.) sein, Standard ist 95.'); ?>
            </p>
            <?php echo $this->formText(
                'compress[fullsize][recompress_max]',
                $params['compress']['fullsize']['recompress_max'],
                array('required' => 'required')); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel(
                'compress[fullsize][recompress_loops]',
                __('Anzahl der Versuchsläufe')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Anzahl der Versuchsläufe festlegen.') . '<br>'
                    . __('Der Wert sollte 10 nicht übersteigen, Standard ist 6.'); ?>
            </p>
            <?php echo $this->formText(
                'compress[fullsize][recompress_loops]',
                $params['compress']['fullsize']['recompress_loops'],
                array('required' => 'required')); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel(
                'compress[fullsize][recompress_method]',
                __('Methode')); ?>
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
                'compress[fullsize][recompress_method]',              // name
                $params['compress']['fullsize']['recompress_method'], // value
                null,                                                           // attribs
                array(
                    'ssim' => 'SSIM',
                    'ms-ssim' => 'MS-SSIM'
                )                                                               // options
            ); ?>
        </div>
    </div>
</fieldset>

<!-- middsize -->
<fieldset>
    <legend><?php echo __('Einstellungen für "Middlesize"'); ?></legend>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel(
                'compress[middsize][recompress_target]',
                __('Ziel-Qualität')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Ziel-Qualität einstellen.') . '<br>'
                    . __('Orientierungshilfe (standard: 0.9999):') . '<br>'
                    . __('niedrig: 0.999 | mittel: 0.9999 | hoch: 0.99995 | sehr hoch: 0.99999'); ?>
            </p>
            <?php echo $this->formText(
                'compress[middsize][recompress_target]',
                $params['compress']['middsize']['recompress_target'],
                array('required' => 'required')); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel(
                'compress[middsize][recompress_min]',
                __('Minimum JPEG Qualität')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Minimum JPEG Qualität einstellen.') . '<br>'
                    . __('Muss kleiner als der Maximalwert (s.u.) sein, Standard ist 40.'); ?>
            </p>
            <?php echo $this->formText(
                'compress[middsize][recompress_min]',
                $params['compress']['middsize']['recompress_min'],
                array('required' => 'required')); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel(
                'compress[middsize][recompress_max]',
                __('Maximum JPEG Qualität')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Maximum JPEG Qualität einstellen.') . '<br>'
                    . __('Muss größer als der Minimalwert (s.o.) sein, Standard ist 95.'); ?>
            </p>
            <?php echo $this->formText(
                'compress[middsize][recompress_max]',
                $params['compress']['middsize']['recompress_max'],
                array('required' => 'required')); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel(
                'compress[middsize][recompress_loops]',
                __('Anzahl der Versuchsläufe')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Anzahl der Versuchsläufe festlegen.') . '<br>'
                    . __('Der Wert sollte 10 nicht übersteigen, Standard ist 6.'); ?>
            </p>
            <?php echo $this->formText(
                'compress[middsize][recompress_loops]',
                $params['compress']['middsize']['recompress_loops'],
                array('required' => 'required')); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel(
                'compress[middsize][recompress_method]',
                __('Methode')); ?>
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
                'compress[middsize][recompress_method]',              // name
                $params['compress']['middsize']['recompress_method'], // value
                null,                                                           // attribs
                array(
                    'ssim' => 'SSIM',
                    'ms-ssim' => 'MS-SSIM'
                )                                                               // options
            ); ?>
        </div>
    </div>
</fieldset>


<!-- thumbnails -->
<fieldset>
    <legend><?php echo __('Einstellungen für Vorschaubilder (thumbnails)'); ?></legend>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel(
                'compress[thumbnails][recompress_target]',
                __('Ziel-Qualität')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Ziel-Qualität einstellen.') . '<br>'
                    . __('Orientierungshilfe (standard: 0.9999):') . '<br>'
                    . __('niedrig: 0.999 | mittel: 0.9999 | hoch: 0.99995 | sehr hoch: 0.99999'); ?>
            </p>
            <?php echo $this->formText(
                'compress[thumbnails][recompress_target]',
                $params['compress']['thumbnails']['recompress_target'],
                array('required' => 'required')); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel(
                'compress[thumbnails][recompress_min]',
                __('Minimum JPEG Qualität')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Minimum JPEG Qualität einstellen.') . '<br>'
                    . __('Muss kleiner als der Maximalwert (s.u.) sein, Standard ist 40.'); ?>
            </p>
            <?php echo $this->formText(
                'compress[thumbnails][recompress_min]',
                $params['compress']['thumbnails']['recompress_min'],
                array('required' => 'required')); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel(
                'compress[thumbnails][recompress_max]',
                __('Maximum JPEG Qualität')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Maximum JPEG Qualität einstellen.') . '<br>'
                    . __('Muss größer als der Minimalwert (s.o.) sein, Standard ist 95.'); ?>
            </p>
            <?php echo $this->formText(
                'compress[thumbnails][recompress_max]',
                $params['compress']['thumbnails']['recompress_max'],
                array('required' => 'required')); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel(
                'compress[thumbnails][recompress_loops]',
                __('Anzahl der Versuchsläufe')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Anzahl der Versuchsläufe festlegen.') . '<br>'
                    . __('Der Wert sollte 10 nicht übersteigen, Standard ist 6.'); ?>
            </p>
            <?php echo $this->formText(
                'compress[thumbnails][recompress_loops]',
                $params['compress']['thumbnails']['recompress_loops'],
                array('required' => 'required')); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel(
                'compress[thumbnails][recompress_method]',
                __('Methode')); ?>
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
                'compress[thumbnails][recompress_method]',              // name
                $params['compress']['thumbnails']['recompress_method'], // value
                null,                                                           // attribs
                array(
                    'ssim' => 'SSIM',
                    'ms-ssim' => 'MS-SSIM'
                )                                                               // options
            ); ?>
        </div>
    </div>
</fieldset>

<!-- square_thumbnails -->
<fieldset>
    <legend><?php echo __('Einstellungen für quadratische Vorschaubilder (square thumbnails)'); ?></legend>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel(
                'compress[square_thumbnails][recompress_target]',
                __('Ziel-Qualität')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Ziel-Qualität einstellen.') . '<br>'
                    . __('Orientierungshilfe (standard: 0.9999):') . '<br>'
                    . __('niedrig: 0.999 | mittel: 0.9999 | hoch: 0.99995 | sehr hoch: 0.99999'); ?>
            </p>
            <?php echo $this->formText(
                'compress[square_thumbnails][recompress_target]',
                $params['compress']['square_thumbnails']['recompress_target'],
                array('required' => 'required')); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel(
                'compress[square_thumbnails][recompress_min]',
                __('Minimum JPEG Qualität')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Minimum JPEG Qualität einstellen.') . '<br>'
                    . __('Muss kleiner als der Maximalwert (s.u.) sein, Standard ist 40.'); ?>
            </p>
            <?php echo $this->formText(
                'compress[square_thumbnails][recompress_min]',
                $params['compress']['square_thumbnails']['recompress_min'],
                array('required' => 'required')); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel(
                'compress[square_thumbnails][recompress_max]',
                __('Maximum JPEG Qualität')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Maximum JPEG Qualität einstellen.') . '<br>'
                    . __('Muss größer als der Minimalwert (s.o.) sein, Standard ist 95.'); ?>
            </p>
            <?php echo $this->formText(
                'compress[square_thumbnails][recompress_max]',
                $params['compress']['square_thumbnails']['recompress_max'],
                array('required' => 'required')); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel(
                'compress[square_thumbnails][recompress_loops]',
                __('Anzahl der Versuchsläufe')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Anzahl der Versuchsläufe festlegen.') . '<br>'
                    . __('Der Wert sollte 10 nicht übersteigen, Standard ist 6.'); ?>
            </p>
            <?php echo $this->formText(
                'compress[square_thumbnails][recompress_loops]',
                $params['compress']['square_thumbnails']['recompress_loops'],
                array('required' => 'required')); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel(
                'compress[square_thumbnails][recompress_method]',
                __('Methode')); ?>
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
                'compress[square_thumbnails][recompress_method]',              // name
                $params['compress']['square_thumbnails']['recompress_method'], // value
                null,                                                           // attribs
                array(
                    'ssim' => 'SSIM',
                    'ms-ssim' => 'MS-SSIM'
                )                                                               // options
            ); ?>
        </div>
    </div>
</fieldset>