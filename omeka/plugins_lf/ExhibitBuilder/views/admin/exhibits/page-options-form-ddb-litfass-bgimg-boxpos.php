<div class="page-options-list clearfix">
    <h2><?php echo __('Optionen'); ?></h2>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel('pageoptions[boxpos]', __('Textblockposition')); ?>
        </div>
        <div class="five columns omega inputs">
        <?php
            $value = '';
            $pageoptions = unserialize($exhibit_page->pageoptions);
            if (isset($pageoptions['boxpos'])) {
                $value = $pageoptions['boxpos'];
            }
            echo $this->formSelect(
                'pageoptions[boxpos]',        // name
                $value,                      // value
                null,                        // attribs
                array(
                    'tl' => 'oben links',
                    'tc' => 'oben zentriert',
                    'tr' => 'oben rechts',
                    'ml' => 'mitte links',
                    'mc' => 'mitte zentriert',
                    'mr' => 'mitte rechts',
                    'bl' => 'unten links',
                    'bc' => 'unten zentriert',
                    'br' => 'unten rechts'
                ),                           // options
                "<br />\n"                   // listsep
            );
        ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel('pageoptions[bgpos]', __('Position des Hintergrundbildes')); ?>
        </div>
        <div class="five columns omega inputs">
        <?php
            $value = 'center center';
            $pageoptions = unserialize($exhibit_page->pageoptions);
            if (isset($pageoptions['bgpos'])) {
                $value = $pageoptions['bgpos'];
            }
            echo $this->formSelect(
                'pageoptions[bgpos]',        // name
                $value,                      // value
                null,                        // attribs
                array(
                    'left top' => 'oben links',
                    'center top' => 'oben zentriert',
                    'right top' => 'oben rechts',
                    'left center' => 'mitte links',
                    'center center' => 'mitte zentriert',
                    'right center' => 'mitte rechts',
                    'left bottom' => 'unten links',
                    'center bottom' => 'unten zentriert',
                    'right bottom' => 'unten rechts'
                ),                           // options
                "<br />\n"                   // listsep
            );
        ?>
        </div>
    </div>
</div>