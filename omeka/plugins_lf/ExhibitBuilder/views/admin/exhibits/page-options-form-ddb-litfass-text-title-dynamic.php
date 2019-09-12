<div class="page-options-list clearfix">
    <h2><?php echo __('Optionen'); ?></h2>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel('options-align', __('Textposition (Fließtext)')); ?>
        </div>
        <div class="five columns omega inputs">
        <?php
            $value = '';
            $pageoptions = unserialize($exhibit_page->pageoptions);
            if (isset($pageoptions['align'])) {
                $value = $pageoptions['align'];
            }
            echo $this->formSelect(
                'pageoptions[align]',        // name
                $value,                      // value
                null,                        // attribs
                array(
                    'left' => 'links',
                    'right' => 'rechts'
                ),                           // options
                "<br />\n"                   // listsep
            );
        ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel('options-title-vertical-align', __('Titelposition')); ?>
        </div>
        <div class="five columns omega inputs">
        <?php
            $value = '';
            $pageoptions = unserialize($exhibit_page->pageoptions);
            if (isset($pageoptions['vertical-align'])) {
                $value = $pageoptions['vertical-align'];
            }
            echo $this->formSelect(
                'pageoptions[vertical-align]',        // name
                $value,                      // value
                null,                        // attribs
                array(
                    'top' => 'oben',
                    'center' => 'mittig',
                    'bottom' => 'unten'
                ),                           // options
                "<br />\n"                   // listsep
            );
        ?>
        </div>
    </div>
</div>