<?php
/**
 * Admin Modifications
 *
 * Keeps original names of files and put them in a hierarchical structure.
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
    protected $_hooks = array();

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
        // var_dump($navArray);
        $counter = 0;
        $new     = array();
        foreach ($navArray as $nav) {
            if (
                ($nav['label'] !== __('Collections') && substr($nav['uri'], -12) !== '/collections')
                &&
                ($nav['label'] !== __('Tags') && substr($nav['uri'], -5) !== '/tags')
                &&
                ($nav['label'] !== __('Tags') && substr($nav['uri'], -11) !== '/item-types')
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
                if ($key !== 'Item Type Metadata') {
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
        // $foo = var_export($text, true) . "\n\n\n";
        // $foo .= var_export($args, true) . "\n\n\n";
        // $foo .= var_export($_POST, true) . "\n\n\n";
        // file_put_contents(__DIR__ . '/test.txt', $foo);
        return $_POST['Elements'][52][0]['text'];
    }

}
