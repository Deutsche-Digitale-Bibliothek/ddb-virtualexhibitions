<?php
/**
* Cortex Import Plugin - Adaption of the CsvImport Plugin 
*
* Imports a given item from an instance of Cortex into the repository of
* Omeka.
*
* @copyright Copyright 2008-2012 Roy Rosenzweig Center for History and New Media, 2013 Fraunhofer IAIS
* @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
* @package CortexImport
*/

defined('CORTEX_IMPORT_DIRECTORY') or define('CORTEX_IMPORT_DIRECTORY', dirname(__FILE__));

/**
 * Cortex Import Plugin
 */
class CortexImportPlugin extends Omeka_Plugin_AbstractPlugin
{
    
    /**
     * @var array Hooks for the plugin.
     */
    protected $_hooks = array(
        'install',
        'uninstall',
        'initialize',
        'admin_head',
        'define_acl',
    );

    /**
     * @var array Filters for the plugin.
     */
    protected $_filters = array(
        'admin_navigation_main',
    );

    /**
     * Install the plugin.
     */
    public function hookInstall()
    {
        $this->_installOptions();
    }

    /**
     * Uninstall the plugin.
     */
    public function hookUninstall()
    {
        $this->_uninstallOptions();
    }

    /**
     * Upgrade the plugin.
     */
    public function hookUpgrade($args)
    {
       
    }

    /**
     * Add the translations.
     */
    public function hookInitialize()
    {
        add_translation_source(dirname(__FILE__) . '/languages');
    }

    /**
     * Define the ACL.
     *
     * @param array $args
     */
    public function hookDefineAcl($args)
    {
        $acl = $args['acl']; // get the Zend_Acl

        $acl->addResource('CortexImport_Index');

        // Hack to disable CRUD actions.
        $acl->deny(null, 'CortexImport_Index', array('show', 'add', 'edit', 'delete'));
        $acl->deny('admin', 'CortexImport_Index');
    }

    /**
     * Configure admin theme header.
     *
     * @param array $args
     */
    public function hookAdminHead($args)
    {
        $request = Zend_Controller_Front::getInstance()->getRequest();
        if ($request->getModuleName() == 'cortex-import') {
            queue_css_file('cortex-import-main');
            queue_js_file('cortex-import');
        }
    }

    /**
     * Add the Simple Pages link to the admin main navigation.
     *
     * @param array Navigation array.
     * @return array Filtered navigation array.
     */
    public function filterAdminNavigationMain($nav)
    {
        $nav[] = array(
            'label' => __('Cortex Import'),
            'uri' => url('cortex-import'),
            'resource' => 'CortexImport_Index',
            'privilege' => 'index',
        );
        return $nav;
    }
}
