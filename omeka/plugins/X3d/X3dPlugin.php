<?php

class X3dPlugin extends Omeka_Plugin_AbstractPlugin
{

    protected $_hooks = array(
        'install',
        'uninstall',
        'define_acl',
        'after_save_item',
        'admin_items_show_sidebar',
        'admin_items_search',
        'public_items_search',
        'items_browse_sql',
        'public_head',
        'initialize',
    );

    protected $_filters = array(
        'admin_navigation_main',
        'public_navigation_main',
        'response_contexts',
        'action_contexts',
        'admin_items_form_tabs',
        'public_navigation_items',
        'api_resources',
        'api_extend_items',
    );

    public function hookInstall()
    {
        $db = $this->_db;
        $sql = "
            CREATE TABLE IF NOT EXISTS `$db->X3d` (
            `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `item_id` BIGINT UNSIGNED NOT NULL,
            `directory` varchar(255) NOT NULL,
            `x3d_file` varchar(255) NOT NULL,
            `texture_file` varchar(255) NOT NULL,
            `thumbnail` varchar(255) NOT NULL,
            INDEX (`item_id`)
            ) ENGINE = InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
        ";
        $db->query($sql);

        $x3dDir = FILES_DIR . DIRECTORY_SEPARATOR . 'x3d';
        if (!is_dir($x3dDir)) {
            mkdir($x3dDir, 0775, true);
        }
    }

    public function hookUninstall()
    {
        // Drop the X3d table
        $db = $this->_db;
        $db->query("DROP TABLE IF EXISTS `$db->X3d`");

        $x3dDir = FILES_DIR . DIRECTORY_SEPARATOR . 'x3d';
        if (is_dir($x3dDir)) {
            $this->_recursiveRemoveDirectory($x3dDir);
        }
        $this->_uninstallOptions();
    }

    protected function _recursiveRemoveDirectory($directory)
    {
        foreach(glob("{$directory}/*") as $file)
        {
            if(is_dir($file)) {
                $this->_recursiveRemoveDirectory($file);
            } elseif ($file != "." && $file != ".." && is_file($file)) {
                unlink($file);
            }
        }
        @rmdir($directory);
    }

    public function hookDefineAcl($args)
    {
        $acl = $args['acl'];
        $acl->addResource('X3d');
        $acl->allow('contributor', 'X3d');
    }

    public function hookDefineRoutes($args)
    {
        $router = $args['router'];
        $mapRoute = new Zend_Controller_Router_Route('x3d',
                        array('controller' => 'x3d',
                                'action'     => 'browse',
                                'module'     => 'x3d'));
        $router->addRoute('x3d', $mapRoute);
    }

    public function hookPublicHead($args)
    {
        queue_css_file('x3d-public');
        queue_js_file('x3dom');
    }

    public function hookAfterSaveItem($args)
    {
        if (!($post = $args['post'])) {
            return;
        }

        $item = $args['record'];

        $x3d = get_db()->getTable('X3d')->findByItemId($item->id);

        $uniqid =  str_replace('.', '', uniqid('', true));

        // Find out if record already exists
        if (isset($x3d)) {

            $x3dDir = FILES_DIR . DIRECTORY_SEPARATOR . 'x3d' . DIRECTORY_SEPARATOR . $x3d->directory;

            // Delete
            if (isset($post['x3d_delete']) && 1 == $post['x3d_delete']) {
                $filesInDir = scandir($x3dDir);
                foreach ($filesInDir as $fileInDir) {
                    if ($fileInDir != "." && $fileInDir != ".." && is_file($x3dDir . DIRECTORY_SEPARATOR . $fileInDir)) {
                        unlink($x3dDir . DIRECTORY_SEPARATOR . $fileInDir);
                    }
                }
                rmdir($x3dDir);
                $x3d->delete();
            }

            if (!is_dir($x3dDir)) {
                return;
            }

            // Update
            $modified = false;

            if (isset($_FILES['x3d_file']['tmp_name']) && is_uploaded_file($_FILES['x3d_file']['tmp_name'])) {

                unlink($x3dDir . DIRECTORY_SEPARATOR . $x3d->x3d_file);

                move_uploaded_file($_FILES['x3d_file']['tmp_name'], $x3dDir . DIRECTORY_SEPARATOR . $_FILES['x3d_file']['name']);

                $x3d->x3d_file = $_FILES['x3d_file']['name'];
                $modified = true;
            }
            if (isset($_FILES['x3d_texture_file']['tmp_name']) && is_uploaded_file($_FILES['x3d_texture_file']['tmp_name'])) {

                unlink($x3dDir . DIRECTORY_SEPARATOR . $x3d->texture_file);

                move_uploaded_file($_FILES['x3d_texture_file']['tmp_name'], $x3dDir . DIRECTORY_SEPARATOR . $_FILES['x3d_texture_file']['name']);

                $x3d->texture_file = $_FILES['x3d_texture_file']['name'];
                $modified = true;
            }
            if (isset($_FILES['x3d_thn_file']['tmp_name']) && is_uploaded_file($_FILES['x3d_thn_file']['tmp_name'])) {

                unlink($x3dDir . DIRECTORY_SEPARATOR . 'or_' .  $x3d->thumbnail);
                unlink($x3dDir . DIRECTORY_SEPARATOR . 'pr_' .  $x3d->thumbnail);
                unlink($x3dDir . DIRECTORY_SEPARATOR . 'sq_' .  $x3d->thumbnail);

                $thnInfo = new SplFileInfo($_FILES['x3d_texture_file']['name']);
                $thnFile = $x3dDir . DIRECTORY_SEPARATOR . 'or_' . $uniqid . '.' . $thnInfo->getExtension();
                move_uploaded_file($_FILES['x3d_thn_file']['tmp_name'], $thnFile);

                $Omeka_File_Derivative_Image_Creator = new Omeka_File_Derivative_Image_Creator('/usr/bin');
                $Omeka_File_Derivative_Image_Creator->addDerivative('pr', 360, false);
                $Omeka_File_Derivative_Image_Creator->addDerivative('sq', 360, true);
                $Omeka_File_Derivative_Image_Creator->create($thnFile, $uniqid . '.' . $thnInfo->getExtension());

                $x3d->thumbnail = $uniqid . '.' . $thnInfo->getExtension();
                $modified = true;
            }

            if (true === $modified) {
                $x3d->save();
            }

        } else {
            // var_dump($_FILES);
            //It's a new record, so we need all three files
            if (!isset($_FILES['x3d_file']) || !isset($_FILES['x3d_texture_file']) || !isset($_FILES['x3d_thn_file']) ||
                (empty($_FILES['x3d_file']['tmp_name']) && empty($_FILES['x3d_texture_file']['tmp_name']) && empty($_FILES['x3d_thn_file']['tmp_name']))) {
                return;
            }
            $x3dDir = FILES_DIR . DIRECTORY_SEPARATOR . 'x3d' . DIRECTORY_SEPARATOR . $uniqid;
            mkdir($x3dDir, 0775, true);
            if (is_uploaded_file($_FILES['x3d_file']['tmp_name'])) {
                move_uploaded_file($_FILES['x3d_file']['tmp_name'], $x3dDir . DIRECTORY_SEPARATOR . $_FILES['x3d_file']['name']);
            }
            if (is_uploaded_file($_FILES['x3d_texture_file']['tmp_name'])) {
                move_uploaded_file($_FILES['x3d_texture_file']['tmp_name'], $x3dDir . DIRECTORY_SEPARATOR . $_FILES['x3d_texture_file']['name']);
            }
            if (is_uploaded_file($_FILES['x3d_thn_file']['tmp_name'])) {

                $thnInfo = new SplFileInfo($_FILES['x3d_texture_file']['name']);
                $thnFile = $x3dDir . DIRECTORY_SEPARATOR . 'or_' . $uniqid . '.' . $thnInfo->getExtension();
                move_uploaded_file($_FILES['x3d_thn_file']['tmp_name'], $thnFile);

                $Omeka_File_Derivative_Image_Creator = new Omeka_File_Derivative_Image_Creator('/usr/bin');
                $Omeka_File_Derivative_Image_Creator->addDerivative('pr', 360, false);
                $Omeka_File_Derivative_Image_Creator->addDerivative('sq', 360, true);
                $Omeka_File_Derivative_Image_Creator->create($thnFile, $uniqid . '.' . $thnInfo->getExtension());
            }

            $newEntry = new X3d;
            $newEntry->item_id = $item->id;
            $newEntry->directory = $uniqid;
            $newEntry->x3d_file = $_FILES['x3d_file']['name'];
            $newEntry->texture_file = $_FILES['x3d_texture_file']['name'];
            if (isset($thnInfo)) {
                $newEntry->thumbnail = $uniqid . '.' . $thnInfo->getExtension();
            }
            $newEntry->save();
        }

    }

    /**
     * Display item relations on the admin items show page.
     *
     * @param Item $item
     */
    public function hookAdminItemsShowSidebar($args)
    {
        $view = $args['view'];
        $item = $args['item'];

        echo common('admin-sidebar', array(
            'x3d' => get_db()->getTable('X3d')->findByItemId($item->id)
        ));
    }

    public function hookPublicItemsShow($args)
    {
        $view = $args['view'];
        $item = $args['item'];
        return;
    }

    public function hookPublicContentTop($args)
    {

    }

    public function hookAdminItemsSearch($args)
    {
        $view = $args['view'];
    }

    public function hookPublicItemsSearch($args)
    {
        $view = $args['view'];
    }

    public function hookItemsBrowseSql($args)
    {
        $db = $this->_db;
    }

    /**
     * Add geolocation search options to filter output.
     *
     * @param array $displayArray
     * @param array $args
     * @return array
     */
    public function filterItemSearchFilters($displayArray, $args)
    {
        $requestArray = $args['request_array'];
        return $displayArray;
    }

    /**
     * Add the translations.
     */
    public function hookInitialize()
    {
        add_translation_source(dirname(__FILE__) . '/languages');
    }

    public function filterAdminNavigationMain($navArray)
    {
        return $navArray;
    }

    public function filterPublicNavigationMain($navArray)
    {
        return $navArray;
    }

    public function filterResponseContexts($contexts)
    {
        return $contexts;
    }

    public function filterActionContexts($contexts, $args)
    {
        return $contexts;
    }

    public function filterAdminItemsFormTabs($tabs, $args)
    {
        $item = $args['item'];
        $db = $this->_db;
        $itemTable = $db->getTable('Item');

        $select = $itemTable->getSelect();
        // $itemTable->filterByItemType($select, $this->itemTypeId{'Ort'});
        $items = $itemTable->fetchObjects($select);
        $x3d = get_db()->getTable('X3d')->findByItemId($item->id);

        ob_start();
        include 'admin_select_x3d_form.php';
        $content = ob_get_contents();
        ob_end_clean();

        $tabname = __('3D-Dateien');

        $tabs[$tabname] = $content; // $content;

        return $tabs;
    }

    public function filterPublicNavigationItems($navArray)
    {
        return $navArray;
    }

    /**
     * Register the geolocations API resource.
     *
     * @param array $apiResources
     * @return array
     */
    public function filterApiResources($apiResources)
    {
        return $apiResources;
    }

    /**
     * Add geolocations to item API representations.
     *
     * @param array $extend
     * @param array $args
     * @return array
     */
    public function filterApiExtendItems($extend, $args)
    {
        return $extend;
    }

    public function hookContributionTypeForm($args)
    {
    }

    public function hookContributionSaveForm($args)
    {
    }

    public function filterExhibitLayouts($layouts)
    {
        return $layouts;
    }

    public function filterApiImportOmekaAdapters($adapters, $args)
    {
        return $adapters;
    }

}
