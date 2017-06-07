<?php // queue_js_file(array('vendor/tiny_mce/tiny_mce')); ?>
<div class="field">
    <div id="gina_admin_mod_dashboard_panel_title_label" class="two columns alpha">
        <label for="gina_admin_mod_dashboard_panel_title"><?php echo __('Dashboard-Panel Titel'); ?></label>
    </div>
    <div class="inputs five columns omega">
        <p class="explanation"><?php echo __(
            'Geben Sie einen Titel für das Dashboard-Panel an.'
        ); ?></p>
        <?php echo
            get_view()->formText(
                'gina_admin_mod_dashboard_panel_title',
                get_option('gina_admin_mod_dashboard_panel_title'),
                array('id' => 'gina_admin_mod_dashboard_panel_title')
            );
        ?>
    </div>
</div>
<div class="field">
    <div id="gina_admin_mod_dashboard_panel_content_label" class="two columns alpha">
        <label for="gina_admin_mod_dashboard_panel_content"><?php echo __('Dashboard-Panel Inhalt'); ?></label>
    </div>
    <div class="inputs five columns omega">
        <p class="explanation"><?php echo __(
            'Geben Sie den Inhalt für das Dashboard-Panel ein.'
        ); ?></p>
        <?php echo
            get_view()->formTextarea(
                'gina_admin_mod_dashboard_panel_content',
                get_option('gina_admin_mod_dashboard_panel_content'),
                array(
                    'id' => 'gina_admin_mod_dashboard_panel_content',
                    'class' => 'html-input'
                )
            );
        ?>
    </div>
</div>