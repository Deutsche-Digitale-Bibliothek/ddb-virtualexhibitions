<?php
/**
 * ExhibitColorSchemeDesignerPlugin
 * @copyright Copyright 2020 Viktor Grandgeorg
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 */

/**
 * ColorSchemeDesigner Plugin.
 */
class ExhibitColorSchemeDesignerPlugin extends Omeka_Plugin_AbstractPlugin
{

    /**
     * @var array Hooks for the plugin.
     */
    protected $_hooks = array(
        'install',
        'uninstall',
        'define_acl',
        'initialize',
        'admin_head'
    );

    protected $_filters = array(
        'admin_navigation_main'
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
     * Add the translations.
     */
    public function hookInitialize()
    {
        // add_translation_source(dirname(__FILE__) . '/languages');
    }

    /**
     * Define the ACL.
     *
     * @param array $args
     */
    public function hookDefineAcl($args)
    {
        $acl = $args['acl'];
        $acl->addResource('ExhibitColorSchemeDesigner');
        $acl->deny(array('admin', 'contributor', 'researcher'), 'ExhibitColorSchemeDesigner');
    }

    public function hookAdminHead()
    {
        $request = Zend_Controller_Front::getInstance()->getRequest();
        $module = $request->getModuleName();
        $controller = $request->getControllerName();

        if ($module == 'exhibit-color-scheme-designer' && $controller == 'index') {
            queue_css_file('palettes');
        }
    }

    public function filterAdminNavigationMain($nav)
    {
        $currentuser = Zend_Registry::get('bootstrap')->getResource('currentuser');
        if($currentuser->role === 'super') {
            $nav[] = array(
                'label'     => __('Farbpalette der Ausstellung'),
                'uri'       => url('exhibit-color-scheme-designer'),
                'resource'  => 'ExhibitColorSchemeDesigner',
                'privilege' => 'super'
            );
        }
        return $nav;
    }

}
