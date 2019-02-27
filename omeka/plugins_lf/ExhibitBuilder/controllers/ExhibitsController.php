<?php
/**
 * @copyright Roy Rosenzweig Center for History and New Media, 2007-2012
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @package ExhibitBuilder
 */

/**
 * Controller for Exhibits.
 *
 * @package ExhibitBuilder
 */
class ExhibitBuilder_ExhibitsController extends Omeka_Controller_AbstractActionController
{
    public function init()
    {
        $this->_helper->db->setDefaultModelName('Exhibit');
    }

    public function _getBrowseRecordsPerPage($pluralName = null)
    {
        if (is_admin_theme()) {
            return (int) get_option('per_page_admin');
        } else {
            return (int) get_option('per_page_public');
        }
    }

    public function browseAction()
    {
        $request = $this->getRequest();
        $sortParam = $request->getParam('sort');
        $sortOptionValue = get_option('exhibit_builder_sort_browse');

        if (!isset($sortParam)) {
            switch ($sortOptionValue) {
                case 'alpha':
                    $request->setParam('sort', 'alpha');
                    break;
                case 'recent':
                    $request->setParam('sort', 'recent');
                    break;
            }
        }

        parent::browseAction();
    }

    public function editAction()
    {
        $fileFields = array('banner', 'cover', 'titlebackground');
        foreach ($fileFields as $fileField) {
            if ($this->getRequest()->isPost() && isset($_FILES[$fileField]) &&
                isset($_FILES[$fileField]['error']) && $_FILES[$fileField]['error'] == 0) {
                $uploadedFile = $_FILES[$fileField];
                $fileName = preg_replace('/[^a-zA-Z0-9\-_\.]/', '-', str_replace('.', '', microtime(true))
                    . '-' . trim($uploadedFile['name']));
                if (!is_dir(FILES_DIR . '/layout/' . $fileField)) {
                    mkdir(FILES_DIR . '/layout/' . $fileField, 0755, true);
                }
                if (move_uploaded_file($uploadedFile['tmp_name'], FILES_DIR . '/layout/' . $fileField . '/' . $fileName)) {
                    $_POST[$fileField] = $fileName;
                }
            }
        }
        parent::editAction();
    }

    protected function _findByExhibitSlug($exhibitSlug = null)
    {
        if (!$exhibitSlug) {
            $exhibitSlug = $this->_getParam('slug');
        }
        $exhibit = $this->_helper->db->getTable()->findBySlug($exhibitSlug);
        return $exhibit;
    }

    public function tagsAction()
    {
        $params = array_merge($this->_getAllParams(), array('type'=>'Exhibit'));
        $tags = $this->_helper->db->getTable('Tag')->findBy($params);
        $this->view->assign(compact('tags'));
    }

    public function showitemAction()
    {

        $itemId = $this->_getParam('item_id');
        $item = $this->_helper->db->findById($itemId, 'Item');

        $exhibit = $this->_findByExhibitSlug();
        if (!$exhibit) {
            throw new Omeka_Controller_Exception_404;
        }

        if ($item && $exhibit->hasItem($item) ) {

            //Plugin hooks
            fire_plugin_hook('show_exhibit_item',  array('item' => $item, 'exhibit' => $exhibit));

            return $this->renderExhibit(compact('exhibit', 'item'), 'item');
        } else {
            $this->_helper->flashMessenger(__('This item is not used within this exhibit.'), 'error');
            throw new Omeka_Controller_Exception_403;
        }
    }

    public function showajaxitemAction()
    {

        // var_dump($this->view);
        $itemId = $this->_getParam('item_id');
        $item = $this->_helper->db->findById($itemId, 'Item');

        $exhibit = $this->_findByExhibitSlug();
        if (!$exhibit) {
            throw new Omeka_Controller_Exception_404;
        }

        if ($item && $exhibit->hasItem($item) ) {

            //Plugin hooks
            // fire_plugin_hook('show_exhibit_item',  array('item' => $item, 'exhibit' => $exhibit));
            // var_dump($this->view);

            // return $this->renderExhibit(compact('exhibit', 'item'), 'item');
            $this->view->assign(compact('exhibit', 'item'));
            return $this->render();
        } else {
            $this->_helper->flashMessenger(__('This item is not used within this exhibit.'), 'error');
            throw new Omeka_Controller_Exception_403;
        }
    }

    public function itemContainerAction()
    {
        $itemId = (int)$this->_getParam('item_id');
        $fileId = (int)$this->_getParam('file_id');
        $orderOnForm = (int)$this->_getParam('order_on_form');

        $item = $this->_helper->db->getTable('Item')->find($itemId);
        $file = $this->_helper->db->getTable('File')->find($fileId);

        $this->view->item = $item;
        $this->view->file = $file;
        $this->view->orderOnForm = $orderOnForm;
    }

    public function showAction()
    {
        $exhibit = $this->_findByExhibitSlug();

        if (!$exhibit) {
            throw new Omeka_Controller_Exception_404;
        }

        $params = $this->getRequest()->getParams();
        unset($params['action']);
        unset($params['controller']);
        unset($params['module']);
        //loop through the page slugs to make sure each one actually exists
        //then render the last one
        //pass all the pages into the view so the breadcrumb can be built there
        unset($params['slug']); // don't need the exhibit slug

        $allowedParams = array(
            'page_slug_1',
            'page_slug_2',
            'page_slug_3'
        );

        $pageTable = $this->_helper->db->getTable('ExhibitPage');

        $parentPage = null;
        foreach($params as $slugKey => $slug) {
            if(!empty($slug) && in_array($slugKey, $allowedParams)) {
                $exhibitPage = $pageTable->findBySlug($slug, $exhibit, $parentPage);
                if($exhibitPage) {
                    $parentPage = $exhibitPage;
                } else {
                    throw new Omeka_Controller_Exception_404;
                }
            }
        }

        fire_plugin_hook('show_exhibit', array('exhibit' => $exhibit, 'exhibitPage' => $exhibitPage));

        $this->renderExhibit(array(
            'exhibit' => $exhibit,
            'exhibit_page' => $exhibitPage));
    }

    public function summaryAction()
    {
        $exhibit = $this->_findByExhibitSlug();
        if (!$exhibit) {
            throw new Omeka_Controller_Exception_404;
        }

        fire_plugin_hook('show_exhibit', array('exhibit' => $exhibit));
        $this->renderExhibit(compact('exhibit'), 'summary');
    }

    /**
     * Figure out how to render the exhibit.
     * 1) the view needs access to the shared directories
     * 2) if the exhibit has an associated theme, render the pages for that specific exhibit theme,
     *      otherwise display the generic theme pages in the main public theme
     *
     * @return void
     **/
    protected function renderExhibit($vars, $toRender = 'show')
    {
        extract($vars);
        $this->view->assign($vars);

        /* If we don't pass a valid value to $toRender, thow an exception. */
        if (!in_array($toRender, array('show', 'summary', 'item'))) {
            throw new Exception( 'You gotta render some stuff because whatever!' );
        }
        return $this->render($toRender);

    }

    protected function _redirectAfterAdd($exhibit)
    {
        if (array_key_exists('add_page', $_POST)) {
            $this->_helper->redirector->gotoRoute(array('action' => 'add-page', 'id' => $exhibit->id), 'exhibitStandard');
        } else {
            $this->_helper->redirector->gotoRoute(array('action' => 'edit', 'id' => $exhibit->id), 'exhibitStandard');
        }
    }

    protected function _redirectAfterEdit($exhibit)
    {
        $this->_redirectAfterAdd($exhibit);
    }

    public function themeConfigAction()
    {
        $exhibit = $this->_helper->db->findById();
        $themeName = (string)$exhibit->theme;

        // Abort if no specific theme is selected.
        if ($themeName == '') {
            $this->_helper->flashMessenger(__('You must specifically select a theme in order to configure it.'), 'error');
            $this->_helper->redirector->gotoRoute(array('action' => 'edit', 'id' => $exhibit->id), 'exhibitStandard');
            return;
        }

        $theme = Theme::getTheme($themeName);
        $previousOptions = $exhibit->getThemeOptions();

        $form = new Omeka_Form_ThemeConfiguration(array(
            'themeName' => $themeName,
            'themeOptions' => $previousOptions
        ));
        $form->removeDecorator('Form');

        $themeConfigIni = $theme->path . DIRECTORY_SEPARATOR . 'config.ini';

        if (file_exists($themeConfigIni) && is_readable($themeConfigIni)) {

            try {
                $pluginsIni = new Zend_Config_Ini($themeConfigIni, 'plugins');
                $excludeFields = $pluginsIni->exclude_fields;
                $excludeFields = explode(',', $excludeFields);

            } catch(Exception $e) {
                $excludeFields = array();
            }

            foreach ($excludeFields as $excludeField) {
                trim($excludeField);
                $form->removeElement($excludeField);
            }
        }

        // process the form if posted
        if ($this->getRequest()->isPost()) {
            $configHelper = new Omeka_Controller_Action_Helper_ThemeConfiguration;

            if (($newOptions = $configHelper->processForm($form, $_POST, $previousOptions))) {
                $exhibit->setThemeOptions($newOptions);
                $exhibit->save();

                $this->_helper->_flashMessenger(__('The theme settings were successfully saved!'), 'success');
                $this->_helper->redirector->gotoRoute(array('action' => 'edit', 'id' => $exhibit->id), 'exhibitStandard');
            } else {
                $this->_helper->_flashMessenger(__('There was an error on the form. Please try again.'), 'error');
            }
        }

        $this->view->assign(compact('exhibit', 'form', 'theme'));
    }


    /**
     * Add a page to an exhibit
     *
     * 1st URL param = 'id' for the exhibit that will contain the page
     *
     **/
    public function addPageAction()
    {
        $db = $this->_helper->db->getDb();
        $request = $this->getRequest();
        $exhibitId = $request->getParam('id');
        //check if a parent page is coming in
        $previousPageId = $request->getParam('previous');
        $exhibitPage = new ExhibitPage;
        $exhibitPage->exhibit_id = $exhibitId;
        $exhibit = $exhibitPage->getExhibit();

        //Set the order for the new page

        if($previousPageId) {
            //set the order to be right after the previous one. Page's beforeSave method will bump up later page orders as needed
            $previousPage = $db->getTable('ExhibitPage')->find($previousPageId);
            $exhibitPage->parent_id = $previousPage->parent_id;
            $exhibitPage->order = $previousPage->order + 1;
        } else {
            $childCount = $exhibit->countTopPages();
            $exhibitPage->order = $childCount +1;
        }



        $success = $this->processPageForm($exhibitPage, 'Add', $exhibit);

        if ($success) {
            $this->_helper->flashMessenger(__("Changes to the exhibit's page were successfully saved!"), 'success');
            $this->_helper->redirector->gotoRoute(array('action' => 'edit-page-content', 'id' => $exhibitPage->id), 'exhibitStandard');
            return;
        }

        $this->render('page-metadata-form');
    }

    public function editPageContentAction()
    {

        $db = $this->_helper->db->getDb();
        $exhibitPage = $this->_helper->db->findById(null,'ExhibitPage');
        $exhibit = $db->getTable('Exhibit')->find($exhibitPage->exhibit_id);


        if (!$this->_helper->acl->isAllowed('edit', $exhibit)) {
            throw new Omeka_Controller_Exception_403;
        }

        $layoutIni = $this->layoutIni($exhibitPage->layout);

        $layoutName = $layoutIni->name;
        $layoutDescription = $layoutIni->description;

        $success = $this->processPageForm($exhibitPage, 'Edit', $exhibit);

        if ($success and array_key_exists('page_metadata_form', $_POST)) {
            $this->_helper->redirector->gotoRoute(array('action' => 'edit-page-metadata', 'id' => $exhibitPage->id), 'exhibitStandard');
            return;
        } else if (array_key_exists('page_form',$_POST)) {
            //Forward to the addPage action (id is the exhibit)
            $this->_helper->redirector->gotoRoute(array('action' => 'add-page', 'id' => $exhibitPage->exhibit_id, 'previous' => $exhibitPage->id), 'exhibitStandard');
            return;
        }

        $this->view->layoutName = $layoutName;
        $this->view->layoutDescription = $layoutDescription;

        $this->render('page-content-form');
    }

    public function editPageMetadataAction()
    {
        $exhibitPage = $this->_helper->db->findById(null,'ExhibitPage');

        $exhibit = $exhibitPage->getExhibit();

        if (!$this->_helper->acl->isAllowed('edit', $exhibit)) {
            throw new Omeka_Controller_Exception_403;
        }

        $success = $this->processPageForm($exhibitPage, 'Edit', $exhibit);

        if ($success) {
            $this->_helper->redirector->gotoRoute(array('action' => 'edit-page-content', 'id' => $exhibitPage->id), 'exhibitStandard');
            return;
        }

        $this->render('page-metadata-form');
    }

    protected function processPageForm($exhibitPage, $actionName, $exhibit = null)
    {
        $this->view->assign(compact('exhibit', 'actionName'));
        $this->view->exhibit_page = $exhibitPage;
        if ($this->getRequest()->isPost()) {
            // Grandgeorg Websolutions
            // var_dump($_POST, $exhibitPage); die();
            $fileFields = array('pagethumbnail');
            foreach ($fileFields as $fileField) {
                if ($this->getRequest()->isPost() && isset($_FILES[$fileField]) &&
                    isset($_FILES[$fileField]['error']) && $_FILES[$fileField]['error'] == 0) {
                    $uploadedFile = $_FILES[$fileField];
                    $fileName = preg_replace('/[^a-zA-Z0-9\-_\.]/', '-', str_replace('.', '', microtime(true))
                        . '-' . trim($uploadedFile['name']));
                    if (!is_dir(FILES_DIR . '/layout/' . $fileField)) {
                        mkdir(FILES_DIR . '/layout/' . $fileField, 0755, true);
                    }
                    if (move_uploaded_file($uploadedFile['tmp_name'], FILES_DIR . '/layout/' . $fileField . '/' . $fileName)) {
                        $_POST[$fileField] = $fileName;
                    }
                }
            }
            //  END Grandgeorg Websolutions
            $exhibitPage->setPostData($_POST);
            // Grandgeorg Websolutions
            if ($this->getRequest()->isPost() && isset($_POST['pageoptions'])) {
                if (isset($_POST['pageoptions']['align'])) {
                    $exhibitPage->pageoptions = serialize($_POST['pageoptions']);
                }
            }
            //  END Grandgeorg Websolutions
            try {
                $success = $exhibitPage->save();
                return true;
            } catch (Exception $e) {
                $this->_helper->flashMessenger($e->getMessage(), 'error');
                return false;
            }
        }
    }

    public function deletePageAction()
    {
        $exhibitPage = $this->_helper->db->findById(null,'ExhibitPage');
        $exhibit = $exhibitPage->getExhibit();
        if (!$this->_helper->acl->isAllowed('delete', $exhibit)) {
            throw new Omeka_Controller_Exception_403;
        }

        $exhibitPage->delete();
        $this->_helper->redirector->gotoUrl('exhibits/edit/' . $exhibit->id );
    }

    protected function findOrNew()
    {
        try {
            $exhibit = $this->_helper->db->findById();
        } catch (Exception $e) {
            $exhibit = new Exhibit;
        }
        return $exhibit;
    }

    protected function layoutIni($layout)
    {
        $iniPath = EXHIBIT_LAYOUTS_DIR . DIRECTORY_SEPARATOR. "$layout" . DIRECTORY_SEPARATOR . "layout.ini";
        if (file_exists($iniPath) && is_readable($iniPath)) {
            $ini = new Zend_Config_Ini($iniPath, 'layout');
            return $ini;
        }
        return false;
    }

    /////END AJAX-ONLY ACTIONS

    public function populatecolorAction()
    {
        $json = '{
            "a": {
              "grey": {
                "hex": "#3d4f58",
                "type": "dark",
                "menu": false
              },
              "red": {
                "hex": "#e73235",
                "type": "dark",
                "menu": false
              },
              "orange": {
                "hex": "#ed6d3f",
                "type": "dark",
                "menu": false
              },
              "yellow": {
                "hex": "#f0e63d",
                "type": "light",
                "menu": false
              },
              "green": {
                "hex": "#4ea643",
                "type": "dark",
                "menu": false
              },
              "blue": {
                "hex": "#4ea643",
                "type": "dark",
                "menu": true
              }
            },
            "b": {
              "grey": {
                "hex": "#3d4f58",
                "type": "dark",
                "menu": false
              },
              "bleu": {
                "hex": "#2593b3",
                "type": "dark",
                "menu": false
              },
              "purple": {
                "hex": "#7c1c60",
                "type": "dark",
                "menu": false
              },
              "orange": {
                "hex": "#e8542c",
                "type": "dark",
                "menu": true
              },
              "oliv": {
                "hex": "#867f4e",
                "type": "dark",
                "menu": false
              }
            },
            "c": {
              "grey": {
                "hex": "#3d4f58",
                "type": "dark",
                "menu": false
              },
              "green": {
                "hex": "#ccdad6",
                "type": "light",
                "menu": false
              },
              "blue": {
                "hex": "#e3f3fe",
                "type": "light",
                "menu": false
              },
              "sand": {
                "hex": "#f6f9ea",
                "type": "light",
                "menu": false
              },
              "apricot": {
                "hex": "#f7cbba",
                "type": "light",
                "menu": true
              },
              "rose": {
                "hex": "#e8b4b4",
                "type": "light",
                "menu": false
              }
            },
            "d": {
              "grey": {
                "hex": "#3d4f58",
                "type": "dark",
                "menu": false
              },
              "melon": {
                "hex": "#eb567e",
                "type": "dark",
                "menu": true
              },
              "sand": {
                "hex": "#d8ba92",
                "type": "light",
                "menu": false
              },
              "powder_sand": {
                "hex": "#f1f0e3",
                "type": "light",
                "menu": false
              },
              "khaki": {
                "hex": "#8ca665",
                "type": "light",
                "menu": false
              },
              "pastell_khaki": {
                "hex": "#bacaa2",
                "type": "light",
                "menu": false
              }
            },
            "e": {
              "blue": {
                "hex": "#274e6c",
                "type": "dark",
                "menu": false
              },
              "creme": {
                "hex": "#fcf3d9",
                "type": "light",
                "menu": false
              },
              "moustard": {
                "hex": "#fdca2f",
                "type": "light",
                "menu": false
              },
              "curry": {
                "hex": "#f59c33",
                "type": "light",
                "menu": false
              },
              "rusty": {
                "hex": "#bb4920",
                "type": "dark",
                "menu": true
              }
            },
            "f": {
              "green": {
                "hex": "#204127",
                "type": "dark",
                "menu": false
              },
              "moss": {
                "hex": "#3e8c35",
                "type": "dark",
                "menu": false
              },
              "blue": {
                "hex": "#274ebc",
                "type": "dark",
                "menu": false
              },
              "rusty": {
                "hex": "#bb4920",
                "type": "dark",
                "menu": true
              },
              "curry": {
                "hex": "#f7a51a",
                "type": "light",
                "menu": false
              },
              "creme": {
                "hex": "#fcf3d9",
                "type": "light",
                "menu": false
              }
            },
            "g": {
              "rose": {
                "hex": "#e2a1ab",
                "type": "dark",
                "menu": false
              },
              "creme": {
                "hex": "#f5efb2",
                "type": "dark",
                "menu": false
              },
              "reed": {
                "hex": "#7ba79a",
                "type": "dark",
                "menu": false
              },
              "iceblue": {
                "hex": "#a6c9d9",
                "type": "dark",
                "menu": true
              },
              "green_grey": {
                "hex": "#537883",
                "type": "light",
                "menu": false
              }
            }
          }';

        $palettes = json_decode($json, true);
        foreach ($palettes as $palette => $colors) {
            foreach ($colors as $color => $props) {
                // $ColorPalette = new ExhibitColorPalette();
                // $ColorPalette->palette = $palette;
                // $ColorPalette->color = $color;
                // $ColorPalette->hex = $props['hex'];
                // $ColorPalette->type = $props['type'];
                // $ColorPalette->menu = ($props['menu'])? 1 : 0;
                // $ColorPalette->save();
            }
        }
    }
}



class ExhibitsController_BadSlug_Exception extends Zend_Controller_Exception
{
}
