<?php
/**
 * Admin Modifications
 *
 * Modify the Admin UI of Omeka to suit our needs
 *
 * @see http://omeka.readthedocs.io/en/latest/Reference/filters/index.html
 * @see http://omeka.readthedocs.io/en/latest/Reference/hooks/index.html
 * @see http://omeka.readthedocs.io/en/latest/Reference/filters/Element_Save_Filter.html
 *
 * @copyright Copyright Grandgeorg Websolutions 2017
 * @license GPLv3
 * @package AdminMod
 */

/**
 * The ImageConvert plugin.
 * @package Omeka\Plugins\ImageConvert
 */
class GinaAdminModPlugin extends Omeka_Plugin_AbstractPlugin
{
    protected $_hooks = array(
        'define_acl',
        'define_routes',
        'initialize',
        'admin_head',
        'config_form',
        'config',
        'admin_items_browse_simple_each',
        'admin_items_form_item_types',
        // 'admin_items_panel_fields',
        'admin_footer_last',
    );

    protected $_filters = array(
        'admin_navigation_main',
        'admin_dashboard_stats',
        'admin_dashboard_panels',
        'admin_items_form_tabs',
        'addItemTypeTitleToDcTitle' => array('Save', 'Item', 'Dublin Core', 'Title')
    );

    /**
     * Modify the admin navigation
     *
     * @param array $navArray The array of admin navigation links
     * @return array
     */
    public function filterAdminNavigationMain($navArray)
    {
        $counter = 0;
        $new     = array();

        $currentuser = Zend_Registry::get('bootstrap')->getResource('currentuser');

        foreach ($navArray as $nav) {
            if (
                ($nav['label'] !== __('Collections') && substr($nav['uri'], -12) !== '/collections')
                &&
                ($nav['label'] !== __('Tags') && substr($nav['uri'], -5) !== '/tags')
                &&
                ($nav['label'] !== __('Item Types') && substr($nav['uri'], -11) !== '/item-types')
            ) {

                if ($nav['label'] === __('Exhibits') && substr($nav['uri'], -9) === '/exhibits') {
                    $new[$counter] = array(
                        'label' => __('Exhibit'),
                        'uri' => $nav['uri'] . '/edit/1',
                        'resource' => $nav['resource'],
                        'privilege' => 'edit',
                    );
                } else {
                    $new[$counter] = $nav;
                }
                $counter++;
            }
            // elseif ($nav['label'] === __('Item Types')
            //     && substr($nav['uri'], -11) === '/item-types'
            //     && $currentuser->role === 'super')
            // {
            //     $new[$counter] = $nav;
            //     $counter++;
            // }
        }
        return $new;
    }

    /**
     * Sections on admin dashboard
     *
     * @param array $stats Array of "statistics" displayed on dashboard
     * @return array
     */
    public function filterAdminDashboardStats($stats)
    {
        $counter = 0;
        $new     = array();
        foreach ($stats as $stat) {
            if (
                ($stat[1] !== 'Kollektionen' && strpos($stat[0], '/collections') === false)
                &&
                ($stat[1] !== 'Tags' && strpos($stat[0], '/tags') === false)
                &&
                ($stat[1] !== 'Plugins' && strpos($stat[0], '/plugins') === false)
                &&
                ($stat[1] !== 'Theme' && strpos($stat[0], '/themes') === false)
                &&
                ($stat[1] !== __('Exhibits') && strpos($stat[0], '/exhibits') === false)
            ) {
                $new[$counter] = $stat;
                $counter++;
            }
        }
        return $new;
    }

    /**
     * Panels on admin dashboard
     *
     * @param array $stats Array of "statistics" displayed on dashboard
     * @return array
     */
    public function filterAdminDashboardPanels($panels)
    {

        $counter = 0;
        $new     = array();
        foreach ($panels as $panel) {
            if (
                (strpos($panel, '/collections/add') === false)
            ) {
                $new[$counter] = $panel;
                $counter++;
            }
        }
        $new[$counter] = '
            <h2>' . get_option('gina_admin_mod_dashboard_panel_title') . '</h2>
            <div class="gina_admin_mod_dashboard_panel_content">'
                . get_option('gina_admin_mod_dashboard_panel_content')
            . '</div>';
        return $new;
    }

    /**
     * Tabs in Admin Item Edit
     *
     * @param array $tabs Array of admin edit Tabs
     * @param array $args Args with Item
     * @return array
     */
    public function filterAdminItemsFormTabs($tabs, $args)
    {
        $new = array();
        foreach ($tabs as $key => $tab) {
            if (
                $key !== 'Dublin Core' &&
                $key !== 'Tags'
            ) {
                if ($key === 'Item Type Metadata') {
                    $new[$key] = $tab
                        . '<input type="hidden" name="Elements[50][0][text]" value="">';
                } else {
                    $new[$key] = $tab;
                }
            }
        }
        return $new;
    }

    /**
     * @param $text input element text
     * @param array $args Args
     * @return string The new value for the element text
     */
    public function addItemTypeTitleToDcTitle($text, $args)
    {
        return $_POST['Elements'][52][0]['text'];
    }

    /**
     * Add the translations.
     */
    public function hookInitialize()
    {
        add_translation_source(dirname(__FILE__) . '/languages');
    }

    /**
     * @param $args array Array with ACL-Object in $args['acl']
     * @return void
     */
    public function hookDefineAcl($args)
    {
        $acl = $args['acl'];
        $acl->deny(null, 'Items', 'makeFeatured');
    }

    /**
     * Add the routes
     *
     * @param Zend_Controller_Router_Rewrite $router
     */
    public function hookDefineRoutes($args)
    {
        // Don't add these routes on the public side to avoid conflicts.
        if (!is_admin_theme()) {
            return;
        }

        $router = $args['router'];

        $router->addRoute(
            'gina-admin-mod',
            new Zend_Controller_Router_Route(
                '/gina-admin-mod/duplicateitem/:id',
                array(
                    'module'     => 'gina-admin-mod',
                    'controller' => 'index',
                    'action'     => 'duplicateitem',
                    'id'         => null
                )
            )
        );

    }

    /**
     * Display the CSS style and javascript for the exhibit in the admin head
     */
    public function hookAdminHead()
    {
        $request = Zend_Controller_Front::getInstance()->getRequest();
        $pluginName = $request->getParam('name');
        if ($pluginName == 'GinaAdminMod') {
            queue_js_file(array('vendor/tiny_mce/tiny_mce', 'config'));
        }
    }

    /**
     * Display the plugin config form.
     */
    public function hookConfigForm()
    {
        require dirname(__FILE__) . '/config_form.php';
    }

    /**
     * Processes the configuration form.
     *
     * @return void
     */
    public function hookConfig($args)
    {
        $post = $args['post'];
        set_option('gina_admin_mod_dashboard_panel_title', $post['gina_admin_mod_dashboard_panel_title']);
        set_option('gina_admin_mod_dashboard_panel_content', $post['gina_admin_mod_dashboard_panel_content']);
    }

    /**
     * Admin items browse
     * @param array array with item and view object
     * @return void
     */
    public function hookAdminItemsBrowseSimpleEach($args)
    {

        echo '<li>'
                . '<a href="'
                . url(array('duplicateitem', 'index', 'gina-admin-mod'), 'gina-admin-mod') . '/'
                . $args['item']->id . '">' . __('Duplizieren') . '</a>'
            . '</li>';
    }

    /**
     *
     * @param array array with item and view object
     * @return void
     */
    // public function hookAdminItemsPanelFields($args)
    // {
    //     // $args['view'];
    //     // $args['record'];
    //     // echo '<h1>fun</h1>';
    // }

    /**
     *
     * @param array array with item and view object
     * @return void
     */
    public function hookAdminItemsFormItemTypes($args)
    {
        // $view = $args['view'];
        // $item = $args['item'];
        echo '<script type="text/javascript" src="'
            . WEB_PLUGIN
            . '/GinaAdminMod/views/admin/javascripts/video-shortcode-helper.js"></script>';
        echo '<script type="text/javascript" src="'
            . WEB_PLUGIN
            . '/GinaAdminMod/views/admin/javascripts/item-meta-fields.js"></script>';
        return;

    }

    public function hookAdminFooterLast()
    {
        $request = Zend_Controller_Front::getInstance()->getRequest();
        $currentUrl = $request->getRequestUri();

        if (strpos($currentUrl, 'admin/items/edit') !== false
            || strpos($currentUrl, 'admin/items/add') !== false) {

            echo '
            <div id="video-shortcode-dialog-form" title="' . __('Video-Shortcode bearbeiten') . '">
            <p class="validateTips"></p>
            <form>
            <fieldset style="padding:0; border:0; margin-top:25px;">
                <label for="video-shortcode-type" style="display:block;">' . __('Typ') . '</label>
                <select name="video-shortcode-type" id="video-shortcode-type"
                    style="display:block; margin-bottom:12px;">
                    <option value=""></option>
                    <option value="ddb">DDB</option>
                    <option value="vimeo">Vimeo</option>
                </select>

                <label for="video-shortcode-id" style="display:block;">' . __('Video-ID') . '</label>
                <input type="text" name="video-shortcode-id" id="video-shortcode-id"
                    value="" class="text ui-widget-content ui-corner-all"
                    style="display:block; margin-bottom:12px; width:95%; padding: .4em;">

                <label for="video-shortcode-start" style="display:block;">' . __('Startzeit in Sekunden (optional)') . '</label>
                <input type="text" name="video-shortcode-start" id="video-shortcode-start"
                    value="" class="text ui-widget-content ui-corner-all"
                    style="display:block; margin-bottom:12px; width:95%; padding: .4em;">

                <label for="video-shortcode-stop" style="display:block;">' . __('Stoppzeit in Sekunden (optional)') . '</label>
                <input type="text" name="video-shortcode-stop" id="video-shortcode-stop"
                    value="" class="text ui-widget-content ui-corner-all"
                    style="display:block; margin-bottom:12px; width:95%; padding: .4em;">
            </fieldset>
            </form>
            </div>
            ';

        }
    }
}
