<!-- original (compressed) -->
<fieldset>
    <legend><?php echo __('Original komprimiert (original compressed)'); ?></legend>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel(
                'compress[original][webp_quality]',
                __('Webp-Qualität')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Webp-Qualität einstellen.') . ' '
                    . __('Bereich zwichen 0 und 100, Standard ist 75.'); ?>
            </p>
            <?php echo $this->formText(
                'compress[original][webp_quality]',
                $params['compress']['original']['webp_quality'],
                array('required' => 'required')); ?>
        </div>
    </div>
</fieldset>

<!-- fullsize -->
<fieldset>
    <legend><?php echo __('Volle Größe (fullsize)'); ?></legend>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel(
                'compress[fullsize][webp_quality]',
                __('Webp-Qualität')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Webp-Qualität einstellen.') . ' '
                    . __('Bereich zwichen 0 und 100, Standard ist 75.'); ?>
            </p>
            <?php echo $this->formText(
                'compress[fullsize][webp_quality]',
                $params['compress']['fullsize']['webp_quality'],
                array('required' => 'required')); ?>
        </div>
    </div>
</fieldset>

<!-- middsize -->
<fieldset>
    <legend><?php echo __('Mittlere Größe (middsize)'); ?></legend>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel(
                'compress[middsize][webp_quality]',
                __('Webp-Qualität')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Webp-Qualität einstellen.') . ' '
                    . __('Bereich zwichen 0 und 100, Standard ist 75.'); ?>
            </p>
            <?php echo $this->formText(
                'compress[middsize][webp_quality]',
                $params['compress']['middsize']['webp_quality'],
                array('required' => 'required')); ?>
        </div>
    </div>
</fieldset>


<!-- thumbnails -->
<fieldset>
    <legend><?php echo __('Vorschau (thumbnails)'); ?></legend>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel(
                'compress[thumbnails][webp_quality]',
                __('Webp-Qualität')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Webp-Qualität einstellen.') . ' '
                    . __('Bereich zwichen 0 und 100, Standard ist 75.'); ?>
            </p>
            <?php echo $this->formText(
                'compress[thumbnails][webp_quality]',
                $params['compress']['thumbnails']['webp_quality'],
                array('required' => 'required')); ?>
        </div>
    </div>
</fieldset>

<!-- square_thumbnails -->
<fieldset>
    <legend><?php echo __('Quadratische Vorschau (square thumbnails)'); ?></legend>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel(
                'compress[square_thumbnails][webp_quality]',
                __('Webp-Qualität')); ?>
        </div>
        <div class="five columns omega inputs">
            <p class="explanation">
                <?php echo __('Webp-Qualität einstellen.') . ' '
                    . __('Bereich zwichen 0 und 100, Standard ist 75.'); ?>
            </p>
            <?php echo $this->formText(
                'compress[square_thumbnails][webp_quality]',
                $params['compress']['square_thumbnails']['webp_quality'],
                array('required' => 'required')); ?>
        </div>
    </div>
</fieldset>