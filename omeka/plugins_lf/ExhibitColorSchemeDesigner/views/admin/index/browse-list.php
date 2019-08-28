<table class="full">
    <thead>
        <tr>
            <?php echo browse_sort_links(array(
                __('Name') => 'name',
                __('Farben') => 'slug',
                __('Last Modified') => 'updated'), array('link_tag' => 'th scope="col"', 'list_tag' => ''));
            ?>
        </tr>
    </thead>
    <tbody>
    <?php if (false): foreach (loop('exhibit_color_schemes') as $colorScheme): ?>
        <tr>
            <td>
                <span class="name">
                    <a href="<?php echo html_escape(record_url('exhibit_color_scheme')); ?>">
                        <?php echo metadata('exhibit_color_scheme', 'name'); ?>
                    </a>
                </span>
                <ul class="action-links group">
                    <li><a class="edit" href="<?php echo html_escape(url('exhibit-color-scheme-designer/index/edit/id/' . metadata('exhibit_color_scheme', 'id'))); ?>">
                        <?php echo __('Edit'); ?>
                    </a></li>
                    <li><a class="delete-confirm" href="<?php echo html_escape(url('exhibit-color-scheme-designer/index/delete-confirm/id/' . metadata('exhibit_color_scheme', 'id'))); ?>">
                        <?php echo __('Delete'); ?>
                    </a></li>
                </ul>
            </td>
            <td style="text-align: center; vertical-align: middle;">
                <div style="
                    display: block;
                    padding: 15px;
                    text-align: center;
                    border: 1px solid <?php echo metadata('exhibit_color_scheme', 'foreground'); ?>;
                    background-color: <?php echo metadata('exhibit_color_scheme', 'background'); ?>;
                    color: <?php echo metadata('exhibit_color_scheme', 'foreground'); ?>;
                ">
                    <p>Hintergrundfarbe: <?php echo metadata('exhibit_color_scheme', 'background'); ?><br>
                    Vordergrundfarbe: <?php echo metadata('exhibit_color_scheme', 'foreground'); ?></p>

                    <div style="
                        display: block;
                        padding: 15px;
                        text-align: center;
                        border: 1px solid <?php echo metadata('exhibit_color_scheme', 'ctrl_foreground'); ?>;
                        background-color: <?php echo metadata('exhibit_color_scheme', 'ctrl_background'); ?>;
                        color: <?php echo metadata('exhibit_color_scheme', 'ctrl_foreground'); ?>;
                        box-shadow:
                            0 5px 11px 0 rgba(0, 0, 0, 0.18),
                            0 4px 15px 0 rgba(0, 0, 0, 0.15);
                        -webkit-box-shadow:
                            0 5px 11px 0 rgba(0, 0, 0, 0.18),
                            0 4px 15px 0 rgba(0, 0, 0, 0.15);
                    ">
                        <strong>Steuerelemente</strong><br>
                        Hintergrundfarbe: <?php echo metadata('exhibit_color_scheme', 'ctrl_background'); ?><br>
                        Vordergrundfarbe: <?php echo metadata('exhibit_color_scheme', 'ctrl_foreground'); ?>


                    </div>

                </div>
            </td>
            <td><?php echo __('<strong>%1$s</strong> on %2$s',
                metadata('exhibit_color_scheme', 'modified_username'),
                html_escape(format_date(metadata('exhibit_color_scheme', 'updated'), Zend_Date::DATETIME_SHORT))); ?>
            </td>
        </tr>
    <?php endforeach; endif; ?>
    </tbody>
</table>
