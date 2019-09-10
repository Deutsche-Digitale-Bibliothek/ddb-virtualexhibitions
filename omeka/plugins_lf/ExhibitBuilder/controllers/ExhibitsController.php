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
    protected $sliderStartPageId = null;

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
        $fileFields = array('banner', 'cover', 'titlebackground', 'titleimage', 'titlelogo', 'startpagethumbnail');
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
        $exhibit = $this->_helper->db->findById();

        if (isset($_POST['deleteTitlebackground']) && $_POST['deleteTitlebackground'] === '1') {
            $_POST['titlebackground'] = '';
            if (is_file(FILES_DIR . '/layout/titlebackground/' . $exhibit->titlebackground)) {
                unlink(FILES_DIR . '/layout/titlebackground/' . $exhibit->titlebackground);
            }
        }

        if (isset($_POST['deleteTitleimage']) && $_POST['deleteTitleimage'] === '1') {
            $_POST['titleimage'] = '';
            if (is_file(FILES_DIR . '/layout/titleimage/' . $exhibit->titleimage)) {
                unlink(FILES_DIR . '/layout/titleimage/' . $exhibit->titleimage);
            }
        }

        if (isset($_POST['deleteTitlelogo']) && $_POST['deleteTitlelogo'] === '1') {
            $_POST['titlelogo'] = '';
            if (is_file(FILES_DIR . '/layout/titlelogo/' . $exhibit->titlelogo)) {
                unlink(FILES_DIR . '/layout/titlelogo/' . $exhibit->titlelogo);
            }
        }

        if (isset($_POST['deleteStartpagethumbnail']) && $_POST['deleteStartpagethumbnail'] === '1') {
            $_POST['startpagethumbnail'] = '';
            if (is_file(FILES_DIR . '/layout/startpagethumbnail/' . $exhibit->startpagethumbnail)) {
                unlink(FILES_DIR . '/layout/startpagethumbnail/' . $exhibit->startpagethumbnail);
            }
        }

        $_POST['institutions'] = $this->setInstitutions($exhibit);

        // Update OMIM
        if (isset($_POST['exhibit_type']) && $exhibit->exhibit_type !== $_POST['exhibit_type']) {
            $db = $this->_helper->db->getDb();
            $prefix = $db->prefix;
            $exhibitNo = (int) substr($prefix, 9, -1);
            $db->update(
                'omim_instances',
                array('exhibit_type' => $_POST['exhibit_type']),
                'id = ' . $exhibitNo
            );
        }
        parent::editAction();
    }

    public function teamAction()
    {
        if(isset($_POST) && isset($_POST['description']) && isset($_POST['team_list'])) {
            $_POST['team'] = serialize(['description' => $_POST['description'], 'team_list' => $_POST['team_list']]);
            unset($_POST['description']);
            unset($_POST['team_list']);
        }
        parent::editAction();
        $this->view->storedTeam = unserialize($this->view->exhibit->team);
    }

    public function imprintAction()
    {
        $modelName = $this->view->singularize($this->_helper->db->getDefaultModelName());
        $record = $this->_helper->db->findById();
        $this->view->$modelName = $record;

        $exhibitType = $this->view->exhibit->exhibit_type;
        $filesDir = realpath($_SERVER['DOCUMENT_ROOT'] . '/../data');
        $masterDoc = file_get_contents($filesDir . '/imprint_' . $exhibitType . '.html');
        $fields = $this->parseImprintShortcode($masterDoc);
        $this->view->masterDoc = $masterDoc;
        $this->view->fields = $fields;

        if ($this->getRequest()->isPost()) {
            $_POST['imprint'] = serialize($this->handleImprintPost($fields));
        }
        parent::editAction();
        $this->view->storedImprint = unserialize($this->view->exhibit->imprint);
    }

    public function parseImprintShortcode($subject)
    {
        preg_match_all('|(\[\[)([^\]\:]+)::([^\]]+)(\]\])|', $subject, $matches, PREG_SET_ORDER);
        $compare = array();
        $result = array();
        foreach ($matches as $key => $value) {
            if (!in_array($value[2], $compare)) {
                array_push($compare, $value[2]);
                array_push($result, array(
                    'var' => $value[2],
                    'desc' => $value[3],
                ));
            }
        }
        return $result;
    }

    public function handleImprintPost($fields)
    {
        $result = array();
        foreach ($fields as $field) {
            if (isset($_POST[$field['var']])) {
                $result[$field['var']] = $_POST[$field['var']];
                unset($_POST[$field['var']]);
            }
        }
        return $result;
    }

    protected function setInstitutions($exhibit)
    {
        $institutions = [];
        if (!isset($_POST['institution']) ||
            empty($_POST['institution']) ||
            !$this->getRequest()->isPost()
        ) {
            return '';
        }

        if (!is_dir(FILES_DIR . '/layout/institutionlogo')) {
            mkdir(FILES_DIR . '/layout/institutionlogo', 0755, true);
        }

        $storedInstitutions = unserialize($exhibit->institutions);
        $allowedTypes = ['image/jpeg', 'image/png', 'image/svg+xml'];

        foreach ($_POST['institution'] as $instKey => $institution) {

            if (isset($institution['delete']) && $institution['delete'] === '1') {
                $storedInstitutions = $this->deleteInstitutionLogo($storedInstitutions, $instKey);
                continue;
            }
            if (isset($institution['name']) && !empty($institution['name'])) {
                $institutions[$instKey]['name'] = strip_tags($institution['name']);
            } else {
                $institutions[$instKey]['name'] = '';
            }
            if (isset($institution['url']) && !empty($institution['url'])) {
                $institutions[$instKey]['url'] = strip_tags($institution['url']);
            } else {
                $institutions[$instKey]['url'] = '';
            }
            if (isset($institution['deletelogo']) && $institution['deletelogo'] === '1') {
                $storedInstitutions = $this->deleteInstitutionLogo($storedInstitutions, $instKey);
            }
            if (isset($_FILES['institution']) &&
                isset($_FILES['institution']['name']) &&
                isset($_FILES['institution']['name'][$instKey]) &&
                isset($_FILES['institution']['name'][$instKey]['logo']) &&
                isset($_FILES['institution']['error']) &&
                isset($_FILES['institution']['error'][$instKey]) &&
                isset($_FILES['institution']['error'][$instKey]['logo']) &&
                $_FILES['institution']['error'][$instKey]['logo'] == 0 &&
                isset($_FILES['institution']['type']) &&
                isset($_FILES['institution']['type'][$instKey]) &&
                isset($_FILES['institution']['type'][$instKey]['logo']) &&
                in_array($_FILES['institution']['type'][$instKey]['logo'], $allowedTypes)
            ) {
                $fileName = preg_replace('/[^a-zA-Z0-9\-_\.]/', '-',
                    str_replace('.', '', microtime(true)) .
                    '-' .
                    trim($_FILES['institution']['name'][$instKey]['logo'])
                );
                if (move_uploaded_file(
                    $_FILES['institution']['tmp_name'][$instKey]['logo'],
                    FILES_DIR . '/layout/institutionlogo/' . $fileName)
                ) {
                    $institutions[$instKey]['logo'] = $fileName;
                }
            } elseif (isset($storedInstitutions) &&
                isset($storedInstitutions[$instKey]) &&
                isset($storedInstitutions[$instKey]['logo'])
            ) {
                $institutions[$instKey]['logo'] = $storedInstitutions[$instKey]['logo'];
            } else {
                $institutions[$instKey]['logo'] = '';
            }
            if (isset($institution['pos']) && !empty($institution['pos'])) {
                $institutions[$instKey]['pos'] = (int) $institution['pos'];
            } else {
                $institutions[$instKey]['pos'] = '';
            }
        }
        return (empty($institutions))? '' : serialize($institutions);
    }

    public function deleteInstitutionLogo($storedInstitutions, $instKey)
    {
        if (isset($storedInstitutions) &&
            isset($storedInstitutions[$instKey]) &&
            isset($storedInstitutions[$instKey]['logo']) &&
            !empty($storedInstitutions[$instKey]['logo'])
        ) {
            if (is_file(FILES_DIR . '/layout/institutionlogo/' . $storedInstitutions[$instKey]['logo'])) {
                unlink(FILES_DIR . '/layout/institutionlogo/' . $storedInstitutions[$instKey]['logo']);
            }
            $storedInstitutions[$instKey]['logo'] = '';
        }
        return $storedInstitutions;
    }

    public function resizeImage($sourcePath, $targetPath, $targetWidth, $targetHeight, $quality = '80')
    {
        $sourcePath = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $sourcePath);
        $targetPath = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $targetPath);

        if (!is_file($sourcePath)) {
            return false;
        }
        $sourcePathParts = pathinfo($sourcePath);
        if (!in_array(strtolower($sourcePathParts['extension']), array('jpg', 'jpeg', 'png')) ||
            !is_dir($sourcePathParts['dirname'])
        ) {
            return false;
        }
        list($sourceWidth, $sourceHeight) = @getimagesize($sourcePath);
        $newHeight = round(($sourceHeight/$sourceWidth)*$targetWidth);
        if ($newHeight > $targetHeight) {
            $newHeight = $targetHeight;
            $newWidth = round(($sourceWidth/$sourceHeight)*$targetHeight);
        } else {
            $newWidth = $targetWidth;
        }
        if (strtolower($sourcePathParts['extension']) === 'png') {
            $newImage = imagecreatefrompng($sourcePath);
        } else {
            $newImage = imagecreatefromjpeg($sourcePath);
        }
        $newTarget = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($newTarget, $newImage, 0, 0, 0, 0, $newWidth, $newHeight, $sourceWidth, $sourceHeight);
        imagejpeg($newTarget, $targetPath, $quality);
        imagedestroy($newTarget);
        return true;
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

        $itemId = $this->_getParam('item_id');
        $item = $this->_helper->db->findById($itemId, 'Item');

        $exhibit = $this->_findByExhibitSlug();
        if (!$exhibit) {
            throw new Omeka_Controller_Exception_404;
        }

        if ($item && $exhibit->hasItem($item) ) {

            // Plugin hooks
            // fire_plugin_hook('show_exhibit_item',  array('item' => $item, 'exhibit' => $exhibit));

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

        if ($this->getRequest()->isPost() && isset($_POST['layout']) && $_POST['layout'] === 'ddb-litfass-slider') {
            $_POST['slug'] = exhibit_builder_generate_slug($_POST['title'] . '_' . date('Y-m-d_h-m-s') . '_start');
            $success = $this->processPageForm($exhibitPage, 'Add', $exhibit, 'start');
            $sliderEnd = new ExhibitPage;
            $sliderEnd->exhibit_id = $exhibitId;
            $sliderEnd->order = $exhibitPage->order + 1;
            $_POST['slug'] = exhibit_builder_generate_slug($_POST['title'] . '_' . date('Y-m-d_h-m-s') . '_end');
            $successSliderEnd = $this->processPageForm($sliderEnd, 'Add', $exhibit, 'end');
        } else {
            $success = $this->processPageForm($exhibitPage, 'Add', $exhibit);
        }

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
            $pageoptions = unserialize($exhibitPage->pageoptions);
            if (isset($pageoptions['slider']) && $pageoptions['slider'] == 'start') {
                $exhibitPage = $this->_helper->db->findById($exhibitPage->id,'ExhibitPage');
                $db = $this->_helper->db;
                $tbl = $db->getTable('ExhibitPage');
                $select = $tbl->getSelect();
                $select->where('exhibit_pages.pageoptions REGEXP ? ', '.*sliderStartPageId\";i:' . $exhibitPage->id . ';');
                $sliderEnd = $tbl->fetchObject($select);
                if ($sliderEnd) {
                    $sliderEnd->title = $exhibitPage->title;
                    $sliderEnd->pagethumbnail = $exhibitPage->pagethumbnail;
                    $sliderEnd->backgroundcolor = $exhibitPage->backgroundcolor;
                    $sliderEnd->save();
                }
            }
            $this->_helper->redirector->gotoRoute(array('action' => 'edit-page-content', 'id' => $exhibitPage->id), 'exhibitStandard');
            return;
        }

        $this->render('page-metadata-form');
    }

    protected function processPageForm($exhibitPage, $actionName, $exhibit = null, $slider = null)
    {
        $this->view->assign(compact('exhibit', 'actionName'));
        $this->view->exhibit_page = $exhibitPage;
        if ($this->getRequest()->isPost()) {
            // Grandgeorg Websolutions
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
                    if ($fileField === 'pagethumbnail') {
                        $pagethumbnail = FILES_DIR . '/layout/' . $fileField . '/' . $fileName;
                        $this->resizeImage($pagethumbnail, $pagethumbnail, 300, 200);
                    }
                }
            }
            if (isset($_POST['deletePagethumbnail']) && $_POST['deletePagethumbnail'] === '1') {
                $_POST['pagethumbnail'] = '';
                if (is_file(FILES_DIR . '/layout/pagethumbnail/' . $exhibitPage->pagethumbnail)) {
                    unlink(FILES_DIR . '/layout/pagethumbnail/' . $exhibitPage->pagethumbnail);
                }
            }
            //  END Grandgeorg Websolutions
            $exhibitPage->setPostData($_POST);
            // Grandgeorg Websolutions
            if ($this->getRequest()->isPost() && isset($_POST['pageoptions']) && is_array($_POST['pageoptions'])) {
                $exhibitPage->pageoptions = serialize($_POST['pageoptions']);
            }
            if (isset($slider)) {
                if ($slider == 'end') {
                    $exhibitPage->pageoptions = serialize(array('slider' => $slider, 'sliderStartPageId' => $this->sliderStartPageId));
                } else {
                    $exhibitPage->pageoptions = serialize(array('slider' => $slider));
                }
            }
            // END Grandgeorg Websolutions
            try {
                $success = $exhibitPage->save();
                if (isset($slider) && $slider == 'start') {
                    $this->sliderStartPageId = $exhibitPage->id;
                }
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
