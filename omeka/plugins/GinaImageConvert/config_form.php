<?php
    $view = get_view();
?>


<!-- original (compressed) -->
<fieldset>
    <legend><?php echo __('Einstellungen für Original - (original compressed)'); ?></legend>
    <h4><?php echo __('Neukompression'); ?></h4>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $view->formLabel(
                'gina_image_convert[original][recompress_target]',
                __('Ziel-Qualität')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Ziel-Qualität einstellen.') . '<br>'
                    . __('Orientierungshilfe (standard: 0.9999):') . '<br>'
                    . __('niedrig: 0.999 | mittel: 0.9999 | hoch: 0.99995 | sehr hoch: 0.99999'); ?>
            </p>
            <?php echo $view->formText(
                'gina_image_convert[original][recompress_target]',
                $params['gina_image_convert']['original']['recompress_target'],
                array('required' => 'required')); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $view->formLabel(
                'gina_image_convert[original][recompress_min]',
                __('Minimum JPEG Qualität')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Minimum JPEG Qualität einstellen.') . '<br>'
                    . __('Muss kleiner als der Maximalwert (s.u.) sein, Standard ist 40.'); ?>
            </p>
            <?php echo $view->formText(
                'gina_image_convert[original][recompress_min]',
                $params['gina_image_convert']['original']['recompress_min'],
                array('required' => 'required')); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $view->formLabel(
                'gina_image_convert[original][recompress_max]',
                __('Maximum JPEG Qualität')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Maximum JPEG Qualität einstellen.') . '<br>'
                    . __('Muss größer als der Minimalwert (s.o.) sein, Standard ist 95.'); ?>
            </p>
            <?php echo $view->formText(
                'gina_image_convert[original][recompress_max]',
                $params['gina_image_convert']['original']['recompress_max'],
                array('required' => 'required')); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $view->formLabel(
                'gina_image_convert[original][recompress_loops]',
                __('Anzahl der Versuchsläufe')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Anzahl der Versuchsläufe festlegen.') . '<br>'
                    . __('Der Wert sollte 10 nicht übersteigen, Standard ist 6.'); ?>
            </p>
            <?php echo $view->formText(
                'gina_image_convert[original][recompress_loops]',
                $params['gina_image_convert']['original']['recompress_loops'],
                array('required' => 'required')); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $view->formLabel(
                'gina_image_convert[original][recompress_method]',
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
            <?php echo $view->formSelect(
                'gina_image_convert[original][recompress_method]',              // name
                $params['gina_image_convert']['original']['recompress_method'], // value
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
    <h4><?php echo __('Größenanpassung'); ?></h4>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $view->formLabel(
                'gina_image_convert[fullsize][resize_max_quality]',
                __('Maximale Qualität bei Größenanpassung')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Maximale JPEG-Qualität bei der Größenanpassung'); ?>
            </p>
            <?php echo $view->formText(
                'gina_image_convert[fullsize][resize_max_quality]',
                $params['gina_image_convert']['fullsize']['resize_max_quality'],
                array('required' => 'required')); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $view->formLabel(
                'gina_image_convert[fullsize][resize_width]',
                __('Breite')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Breite in Pixeln bei Größenanpassung'); ?>
            </p>
            <?php echo $view->formText(
                'gina_image_convert[fullsize][resize_width]',
                $params['gina_image_convert']['fullsize']['resize_width'],
                array('required' => 'required')); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $view->formLabel(
                'gina_image_convert[fullsize][resize_height]',
                __('Höhe')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Höhe in Pixeln bei Größenanpassung'); ?>
            </p>
            <?php echo $view->formText(
                'gina_image_convert[fullsize][resize_height]',
                $params['gina_image_convert']['fullsize']['resize_height'],
                array('required' => 'required')); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $view->formLabel(
                'gina_image_convert[fullsize][resize_square]',
                __('Quadratisch')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Quadratische Bilder generieren (Seitenlänge = Breite)'); ?>
            </p>
            <?php echo $view->formSelect(
                'gina_image_convert[fullsize][resize_square]',                  // name
                $params['gina_image_convert']['fullsize']['resize_square'],     // value
                null,                                                           // attribs
                array(
                    '0' => 'Nein',
                    '1' => 'Ja'
                )                                                               // options
            ); ?>
        </div>
    </div>
    <h4><?php echo __('Neukompression'); ?></h4>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $view->formLabel(
                'gina_image_convert[fullsize][recompress_target]',
                __('Ziel-Qualität')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Ziel-Qualität einstellen.') . '<br>'
                    . __('Orientierungshilfe (standard: 0.9999):') . '<br>'
                    . __('niedrig: 0.999 | mittel: 0.9999 | hoch: 0.99995 | sehr hoch: 0.99999'); ?>
            </p>
            <?php echo $view->formText(
                'gina_image_convert[fullsize][recompress_target]',
                $params['gina_image_convert']['fullsize']['recompress_target'],
                array('required' => 'required')); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $view->formLabel(
                'gina_image_convert[fullsize][recompress_min]',
                __('Minimum JPEG Qualität')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Minimum JPEG Qualität einstellen.') . '<br>'
                    . __('Muss kleiner als der Maximalwert (s.u.) sein, Standard ist 40.'); ?>
            </p>
            <?php echo $view->formText(
                'gina_image_convert[fullsize][recompress_min]',
                $params['gina_image_convert']['fullsize']['recompress_min'],
                array('required' => 'required')); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $view->formLabel(
                'gina_image_convert[fullsize][recompress_max]',
                __('Maximum JPEG Qualität')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Maximum JPEG Qualität einstellen.') . '<br>'
                    . __('Muss größer als der Minimalwert (s.o.) sein, Standard ist 95.'); ?>
            </p>
            <?php echo $view->formText(
                'gina_image_convert[fullsize][recompress_max]',
                $params['gina_image_convert']['fullsize']['recompress_max'],
                array('required' => 'required')); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $view->formLabel(
                'gina_image_convert[fullsize][recompress_loops]',
                __('Anzahl der Versuchsläufe')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Anzahl der Versuchsläufe festlegen.') . '<br>'
                    . __('Der Wert sollte 10 nicht übersteigen, Standard ist 6.'); ?>
            </p>
            <?php echo $view->formText(
                'gina_image_convert[fullsize][recompress_loops]',
                $params['gina_image_convert']['fullsize']['recompress_loops'],
                array('required' => 'required')); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $view->formLabel(
                'gina_image_convert[fullsize][recompress_method]',
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
            <?php echo $view->formSelect(
                'gina_image_convert[fullsize][recompress_method]',              // name
                $params['gina_image_convert']['fullsize']['recompress_method'], // value
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
    <h4><?php echo __('Größenanpassung'); ?></h4>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $view->formLabel(
                'gina_image_convert[middsize][resize_max_quality]',
                __('Maximale Qualität bei Größenanpassung')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Maximale JPEG-Qualität bei der Größenanpassung'); ?>
            </p>
            <?php echo $view->formText(
                'gina_image_convert[middsize][resize_max_quality]',
                $params['gina_image_convert']['middsize']['resize_max_quality'],
                array('required' => 'required')); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $view->formLabel(
                'gina_image_convert[middsize][resize_width]',
                __('Breite')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Breite in Pixeln bei Größenanpassung'); ?>
            </p>
            <?php echo $view->formText(
                'gina_image_convert[middsize][resize_width]',
                $params['gina_image_convert']['middsize']['resize_width'],
                array('required' => 'required')); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $view->formLabel(
                'gina_image_convert[middsize][resize_height]',
                __('Höhe')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Höhe in Pixeln bei Größenanpassung'); ?>
            </p>
            <?php echo $view->formText(
                'gina_image_convert[middsize][resize_height]',
                $params['gina_image_convert']['middsize']['resize_height'],
                array('required' => 'required')); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $view->formLabel(
                'gina_image_convert[middsize][resize_square]',
                __('Quadratisch')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Quadratische Bilder generieren (Seitenlänge = Breite)'); ?>
            </p>
            <?php echo $view->formSelect(
                'gina_image_convert[middsize][resize_square]',                  // name
                $params['gina_image_convert']['middsize']['resize_square'],     // value
                null,                                                           // attribs
                array(
                    '0' => 'Nein',
                    '1' => 'Ja'
                )                                                               // options
            ); ?>
        </div>
    </div>
    <h4><?php echo __('Neukompression'); ?></h4>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $view->formLabel(
                'gina_image_convert[middsize][recompress_target]',
                __('Ziel-Qualität')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Ziel-Qualität einstellen.') . '<br>'
                    . __('Orientierungshilfe (standard: 0.9999):') . '<br>'
                    . __('niedrig: 0.999 | mittel: 0.9999 | hoch: 0.99995 | sehr hoch: 0.99999'); ?>
            </p>
            <?php echo $view->formText(
                'gina_image_convert[middsize][recompress_target]',
                $params['gina_image_convert']['middsize']['recompress_target'],
                array('required' => 'required')); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $view->formLabel(
                'gina_image_convert[middsize][recompress_min]',
                __('Minimum JPEG Qualität')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Minimum JPEG Qualität einstellen.') . '<br>'
                    . __('Muss kleiner als der Maximalwert (s.u.) sein, Standard ist 40.'); ?>
            </p>
            <?php echo $view->formText(
                'gina_image_convert[middsize][recompress_min]',
                $params['gina_image_convert']['middsize']['recompress_min'],
                array('required' => 'required')); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $view->formLabel(
                'gina_image_convert[middsize][recompress_max]',
                __('Maximum JPEG Qualität')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Maximum JPEG Qualität einstellen.') . '<br>'
                    . __('Muss größer als der Minimalwert (s.o.) sein, Standard ist 95.'); ?>
            </p>
            <?php echo $view->formText(
                'gina_image_convert[middsize][recompress_max]',
                $params['gina_image_convert']['middsize']['recompress_max'],
                array('required' => 'required')); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $view->formLabel(
                'gina_image_convert[middsize][recompress_loops]',
                __('Anzahl der Versuchsläufe')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Anzahl der Versuchsläufe festlegen.') . '<br>'
                    . __('Der Wert sollte 10 nicht übersteigen, Standard ist 6.'); ?>
            </p>
            <?php echo $view->formText(
                'gina_image_convert[middsize][recompress_loops]',
                $params['gina_image_convert']['middsize']['recompress_loops'],
                array('required' => 'required')); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $view->formLabel(
                'gina_image_convert[middsize][recompress_method]',
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
            <?php echo $view->formSelect(
                'gina_image_convert[middsize][recompress_method]',              // name
                $params['gina_image_convert']['middsize']['recompress_method'], // value
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
    <h4><?php echo __('Größenanpassung'); ?></h4>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $view->formLabel(
                'gina_image_convert[thumbnails][resize_max_quality]',
                __('Maximale Qualität bei Größenanpassung')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Maximale JPEG-Qualität bei der Größenanpassung'); ?>
            </p>
            <?php echo $view->formText(
                'gina_image_convert[thumbnails][resize_max_quality]',
                $params['gina_image_convert']['thumbnails']['resize_max_quality'],
                array('required' => 'required')); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $view->formLabel(
                'gina_image_convert[thumbnails][resize_width]',
                __('Breite')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Breite in Pixeln bei Größenanpassung'); ?>
            </p>
            <?php echo $view->formText(
                'gina_image_convert[thumbnails][resize_width]',
                $params['gina_image_convert']['thumbnails']['resize_width'],
                array('required' => 'required')); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $view->formLabel(
                'gina_image_convert[thumbnails][resize_height]',
                __('Höhe')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Höhe in Pixeln bei Größenanpassung'); ?>
            </p>
            <?php echo $view->formText(
                'gina_image_convert[thumbnails][resize_height]',
                $params['gina_image_convert']['thumbnails']['resize_height'],
                array('required' => 'required')); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $view->formLabel(
                'gina_image_convert[thumbnails][resize_square]',
                __('Quadratisch')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Quadratische Bilder generieren (Seitenlänge = Breite)'); ?>
            </p>
            <?php echo $view->formSelect(
                'gina_image_convert[thumbnails][resize_square]',                  // name
                $params['gina_image_convert']['thumbnails']['resize_square'],     // value
                null,                                                           // attribs
                array(
                    '0' => 'Nein',
                    '1' => 'Ja'
                )                                                               // options
            ); ?>
        </div>
    </div>
    <h4><?php echo __('Neukompression'); ?></h4>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $view->formLabel(
                'gina_image_convert[thumbnails][recompress_target]',
                __('Ziel-Qualität')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Ziel-Qualität einstellen.') . '<br>'
                    . __('Orientierungshilfe (standard: 0.9999):') . '<br>'
                    . __('niedrig: 0.999 | mittel: 0.9999 | hoch: 0.99995 | sehr hoch: 0.99999'); ?>
            </p>
            <?php echo $view->formText(
                'gina_image_convert[thumbnails][recompress_target]',
                $params['gina_image_convert']['thumbnails']['recompress_target'],
                array('required' => 'required')); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $view->formLabel(
                'gina_image_convert[thumbnails][recompress_min]',
                __('Minimum JPEG Qualität')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Minimum JPEG Qualität einstellen.') . '<br>'
                    . __('Muss kleiner als der Maximalwert (s.u.) sein, Standard ist 40.'); ?>
            </p>
            <?php echo $view->formText(
                'gina_image_convert[thumbnails][recompress_min]',
                $params['gina_image_convert']['thumbnails']['recompress_min'],
                array('required' => 'required')); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $view->formLabel(
                'gina_image_convert[thumbnails][recompress_max]',
                __('Maximum JPEG Qualität')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Maximum JPEG Qualität einstellen.') . '<br>'
                    . __('Muss größer als der Minimalwert (s.o.) sein, Standard ist 95.'); ?>
            </p>
            <?php echo $view->formText(
                'gina_image_convert[thumbnails][recompress_max]',
                $params['gina_image_convert']['thumbnails']['recompress_max'],
                array('required' => 'required')); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $view->formLabel(
                'gina_image_convert[thumbnails][recompress_loops]',
                __('Anzahl der Versuchsläufe')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Anzahl der Versuchsläufe festlegen.') . '<br>'
                    . __('Der Wert sollte 10 nicht übersteigen, Standard ist 6.'); ?>
            </p>
            <?php echo $view->formText(
                'gina_image_convert[thumbnails][recompress_loops]',
                $params['gina_image_convert']['thumbnails']['recompress_loops'],
                array('required' => 'required')); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $view->formLabel(
                'gina_image_convert[thumbnails][recompress_method]',
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
            <?php echo $view->formSelect(
                'gina_image_convert[thumbnails][recompress_method]',              // name
                $params['gina_image_convert']['thumbnails']['recompress_method'], // value
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
    <h4><?php echo __('Größenanpassung'); ?></h4>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $view->formLabel(
                'gina_image_convert[square_thumbnails][resize_max_quality]',
                __('Maximale Qualität bei Größenanpassung')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Maximale JPEG-Qualität bei der Größenanpassung'); ?>
            </p>
            <?php echo $view->formText(
                'gina_image_convert[square_thumbnails][resize_max_quality]',
                $params['gina_image_convert']['square_thumbnails']['resize_max_quality'],
                array('required' => 'required')); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $view->formLabel(
                'gina_image_convert[square_thumbnails][resize_width]',
                __('Breite')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Breite in Pixeln bei Größenanpassung'); ?>
            </p>
            <?php echo $view->formText(
                'gina_image_convert[square_thumbnails][resize_width]',
                $params['gina_image_convert']['square_thumbnails']['resize_width'],
                array('required' => 'required')); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $view->formLabel(
                'gina_image_convert[square_thumbnails][resize_height]',
                __('Höhe')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Höhe in Pixeln bei Größenanpassung'); ?>
            </p>
            <?php echo $view->formText(
                'gina_image_convert[square_thumbnails][resize_height]',
                $params['gina_image_convert']['square_thumbnails']['resize_height'],
                array('required' => 'required')); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $view->formLabel(
                'gina_image_convert[square_thumbnails][resize_square]',
                __('Quadratisch')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Quadratische Bilder generieren (Seitenlänge = Breite)'); ?>
            </p>
            <?php echo $view->formSelect(
                'gina_image_convert[square_thumbnails][resize_square]',                  // name
                $params['gina_image_convert']['square_thumbnails']['resize_square'],     // value
                null,                                                           // attribs
                array(
                    '0' => 'Nein',
                    '1' => 'Ja'
                )                                                               // options
            ); ?>
        </div>
    </div>
    <h4><?php echo __('Neukompression'); ?></h4>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $view->formLabel(
                'gina_image_convert[square_thumbnails][recompress_target]',
                __('Ziel-Qualität')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Ziel-Qualität einstellen.') . '<br>'
                    . __('Orientierungshilfe (standard: 0.9999):') . '<br>'
                    . __('niedrig: 0.999 | mittel: 0.9999 | hoch: 0.99995 | sehr hoch: 0.99999'); ?>
            </p>
            <?php echo $view->formText(
                'gina_image_convert[square_thumbnails][recompress_target]',
                $params['gina_image_convert']['square_thumbnails']['recompress_target'],
                array('required' => 'required')); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $view->formLabel(
                'gina_image_convert[square_thumbnails][recompress_min]',
                __('Minimum JPEG Qualität')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Minimum JPEG Qualität einstellen.') . '<br>'
                    . __('Muss kleiner als der Maximalwert (s.u.) sein, Standard ist 40.'); ?>
            </p>
            <?php echo $view->formText(
                'gina_image_convert[square_thumbnails][recompress_min]',
                $params['gina_image_convert']['square_thumbnails']['recompress_min'],
                array('required' => 'required')); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $view->formLabel(
                'gina_image_convert[square_thumbnails][recompress_max]',
                __('Maximum JPEG Qualität')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Maximum JPEG Qualität einstellen.') . '<br>'
                    . __('Muss größer als der Minimalwert (s.o.) sein, Standard ist 95.'); ?>
            </p>
            <?php echo $view->formText(
                'gina_image_convert[square_thumbnails][recompress_max]',
                $params['gina_image_convert']['square_thumbnails']['recompress_max'],
                array('required' => 'required')); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $view->formLabel(
                'gina_image_convert[square_thumbnails][recompress_loops]',
                __('Anzahl der Versuchsläufe')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Anzahl der Versuchsläufe festlegen.') . '<br>'
                    . __('Der Wert sollte 10 nicht übersteigen, Standard ist 6.'); ?>
            </p>
            <?php echo $view->formText(
                'gina_image_convert[square_thumbnails][recompress_loops]',
                $params['gina_image_convert']['square_thumbnails']['recompress_loops'],
                array('required' => 'required')); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $view->formLabel(
                'gina_image_convert[square_thumbnails][recompress_method]',
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
            <?php echo $view->formSelect(
                'gina_image_convert[square_thumbnails][recompress_method]',              // name
                $params['gina_image_convert']['square_thumbnails']['recompress_method'], // value
                null,                                                           // attribs
                array(
                    'ssim' => 'SSIM',
                    'ms-ssim' => 'MS-SSIM'
                )                                                               // options
            ); ?>
        </div>
    </div>
</fieldset>