<?php
/**
 * ExhibitColorSchemeDesignerPlugin
 * @copyright Copyright 2016 Viktor Grandgeorg
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 */

/**
 * ColorSchemeDesigner Plugin.
 */
class ExhibitColorSchemeDesignerPlugin extends Omeka_Plugin_AbstractPlugin
{

    public $itemTypeId = array();

    /**
     * @var array Hooks for the plugin.
     */
    protected $_hooks = array(
        'install',
        'uninstall',
        'define_acl',
        'initialize'
    );

    protected $_filters = array(
        'admin_navigation_main'
    );

    /**
     * Install the plugin.
     */
    public function hookInstall()
    {
        // Create tables.
        $db = $this->_db;

        $sql = "DROP TABLE IF EXISTS `$db->ExhibitColorScheme`";
        $db->query($sql);

        $sql = "
        CREATE TABLE IF NOT EXISTS `$db->ExhibitColorScheme` (
            `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
            `background` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
            `foreground` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
            `ctrl_background` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
            `ctrl_foreground` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
            `created_by_user_id` int(10) unsigned NOT NULL,
            `modified_by_user_id` int(10) unsigned NOT NULL,
            `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            `inserted` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
            PRIMARY KEY (`id`),
            KEY `inserted` (`inserted`),
            KEY `updated` (`updated`),
            KEY `created_by_user_id` (`created_by_user_id`),
            KEY `modified_by_user_id` (`modified_by_user_id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
        $db->query($sql);

        $page = new ExhibitColorScheme;
        $page->name = 'dunkel';
        $page->background = '#333';
        $page->foreground = '#eee';
        $page->ctrl_background = '#333';
        $page->ctrl_foreground = '#eee';
        $page->modified_by_user_id = current_user()->id;
        $page->created_by_user_id = current_user()->id;
        $page->save();

        $page = new ExhibitColorScheme;
        $page->name = 'hell';
        $page->background = '#fff';
        $page->foreground = '#333';
        $page->ctrl_background = '#f6f6f6';
        $page->ctrl_foreground = '#333';

        $page->modified_by_user_id = current_user()->id;
        $page->created_by_user_id = current_user()->id;
        $page->save();

        $this->_installOptions();
    }

    /**
     * Uninstall the plugin.
     */
    public function hookUninstall()
    {
        $db = $this->_db;
        $sql = "DROP TABLE IF EXISTS `$db->ExhibitColorScheme`";
        $db->query($sql);

        $this->_uninstallOptions();
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
        $acl = $args['acl'];
        $acl->addResource('ExhibitColorSchemeDesigner');
        $acl->allow(null, 'ExhibitColorSchemeDesigner');
    }

    public function filterAdminNavigationMain($nav)
    {
        $nav[] = array(
            'label'     => __('Farbschemata für Ausstellungen'),
            'uri'       => url('exhibit-color-scheme-designer'),
            'resource'  => 'ExhibitColorSchemeDesigner',
            'privilege' => 'browse'
        );
        return $nav;
    }

}
