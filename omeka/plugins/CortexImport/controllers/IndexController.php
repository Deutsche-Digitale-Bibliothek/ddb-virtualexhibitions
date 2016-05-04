<?php

/**
 * CortexImport_IndexController class - represents the Cortex Import index controller
 *
 * Adaption of CsvImport_IndexController.
 *
 * @copyright Copyright 2008-2012 Roy Rosenzweig Center for History and New Media, Fraunhofer IAIS 2013
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 * @package CortexImport
 */
class CortexImport_IndexController extends Omeka_Controller_AbstractActionController
{

    /**
     * Initialize the controller.
     */
    public function init()
    {
        $this->session = new Zend_Session_Namespace('CortexImport');
        $this->_helper->db->setDefaultModelName('CortexImport_Import');
    }

    /**
     * Configure a new import.
     */
    public function indexAction()
    {
        $form = $this->_getMainForm();
        $this->view->form = $form;

        if ($this->session->successfulLastImport) {
            $this->_helper->flashMessenger('Successfully imported item with id ' . $this->session->successfulLastImport . '.', 'success');
            $this->session->successfulLastImport = null;
            return;
        }
        
        if (! $this->getRequest()->isPost()) {
            return;
        }
        
        if (! $form->isValid($this->getRequest()
            ->getPost())) {
            $this->_helper->flashMessenger('Invalid form input. Please see errors below and try again.', 'error');
            return;
        }
        
        $this->session->hostUrl = $form->getValue('host_url');
        $this->session->apiKey = $form->getValue('api_key');
        $this->session->ddbItemId = $form->getValue('cortex_id');
        
        $item = new CortexImport_Item($this->_getCortexItem());
        
//         $this->session->setExpirationHops(2);
        $this->session->fieldValues = $item->getAllFieldValues();
        $this->session->ownerId = $this->getInvokeArg('bootstrap')->currentuser->id;
        
        $this->_helper->redirector->goto('verify');
    }

    private function _getCortexItem()
    {
        $client = new Zend_Http_Client($this->session->hostUrl . '/items/' . $this->session->ddbItemId . '/edm?oauth_consumer_key=' . $this->session->apiKey);
        $client->setHeaders('Accept:application/xml');
        
        $response = $client->request('GET');
        
        if ($response->getStatus() == 404) {
        	$this->_helper->flashMessenger('The item ' . $this->session->ddbItemId . ' has no EDM data or does not exist.', 'error');
        	$this->_helper->redirector->goto('index');
        }
        
        if ($response->getStatus() != 200) {
            $this->_helper->flashMessenger('The request returned a code ' . $response->getStatus() . '.', 'error');
            $this->_helper->redirector->goto('index');
        }
        
        return $response->getBody();
    }

    public function verifyAction()
    {
        
        $form = $this->_getVerifyForm();
        $this->view->form = $form;
        
        if (! $this->getRequest()->isPost()) {
            return;
        }
        if (! $form->isValid($this->getRequest()
            ->getPost())) {
            $this->_helper->flashMessenger(__('Invalid form input. Please try again.'), 'error');
            return;
        }
        
        // Get cortex item field values
        $fieldValues = array();
        foreach (CortexImport_Item::getDCFieldNames() as $index => $dcField) {
            $fieldValues[$dcField] = $form->getValue($dcField);
        }
        $this->session->fieldValues = $fieldValues;
        
        $db = get_db();
        
        // put omeka item types into session
        $itemTypes = $db->getTable('ItemType')->findBy(array());
        $this->session->itemTypes = $itemTypes;
        
        // put omeka collections into session
        $collections = get_records('ElementText', array(
            'record_type' => 'Collection'
        ), 1000);
        $this->session->collections = $collections;
        
        $this->_helper->redirector->goto('save');
    }

    public function saveAction()
    {
        $form = $this->_getSaveForm();
        $this->view->form = $form;
        
        if (! $this->getRequest()->isPost()) {
            return;
        }
        
        if (! $form->isValid($this->getRequest()
            ->getPost())) {
            $this->_helper->flashMessenger(__('Invalid form input. Please try again.'), 'error');
            return;
        }
        
        // get item type id
        $itemTypeId;
        $selectedItemTypeIndex = $form->getValue('item_type_id');
        $itemTypeOptions = $form->getElement('item_type_id')->getMultiOptions();
        $itemTypeLabel = $itemTypeOptions[$selectedItemTypeIndex];
        foreach ($this->session->itemTypes as $itemType) {
        	if ($itemType->name == $itemTypeLabel) {
        	    $itemTypeId = $itemType->id;
        	    break;
        	}
        }

        // get collection id
        $collectionId;
        $selectedCollectionIndex = $form->getValue('collection_id');
        $collectionOptions = $form->getElement('collection_id')->getMultiOptions();
        $collectionLabel = $collectionOptions[$selectedCollectionIndex];
        foreach ($this->session->collections as $collection) {
        if ($collection->text == $collectionLabel) {
        	    $collectionId = $collection->record_id;
        	    break;
        	}
        }
        
        
        $itemMetadata = array(
            Builder_Item::IS_PUBLIC => $form->getValue('is_public'),
            Builder_Item::IS_FEATURED => $form->getValue('is_featured'),
            Builder_Item::ITEM_TYPE_ID => $itemTypeId,
            Builder_Item::COLLECTION_ID => $collectionId,
            Builder_Item::TAGS => $form->getValue('tags')
        );
        
        $fieldValues = $this->session->fieldValues;
        $elementsText = array();
        foreach (CortexImport_Item::getDCFieldNames() as $index => $dcField) {
            $elementsText['Dublin Core'][ucfirst($dcField)][] = array(
                'text' => $fieldValues[$dcField],
                'html' => false
            );
        }
        
        $item = insert_item($itemMetadata, $elementsText);
        
//         $ingest = Omeka_File_Ingest_AbstractIngest::factory('Url', $item);
//         $fileRecords = $ingest->ingest($this->session->hostUrl . '/binary/' . $this->session->ddbItemId . '/full/1.jpg?oauth_consumer_key=' . $this->session->apiKey);
        
        $this->session->successfulLastImport = $this->session->ddbItemId;
        
        $this->_helper->redirector->goto('index');
    }
    
    public function binaryAction() {
        
    }

    /**
     * Get the main Csv Import form.
     *
     * @return CsvImport_Form_Main
     */
    protected function _getMainForm()
    {
        require_once CORTEX_IMPORT_DIRECTORY . '/forms/Main.php';
        $form = new CortexImport_Form_Main(array(
            'url' => $this->session->hostUrl,
            'key' => $this->session->apiKey,
            'itemId' => $this->session->ddbItemID
        ));
        return $form;
    }

    protected function _getVerifyForm()
    {
        require_once CORTEX_IMPORT_DIRECTORY . '/forms/Verify.php';
        $form = new CortexImport_Form_Verify(array(
            'fieldValues' => $this->session->fieldValues
        ));
        return $form;
    }

    protected function _getSaveForm()
    {
        $itemTypeNames = array();
        foreach ($this->session->itemTypes as $itemType) {
        	array_push($itemTypeNames, $itemType->name);
        }

        $collectionNames = array();
        foreach ($this->session->collections as $collection) {
        	array_push($collectionNames, $collection->text);
        }
        
        require_once CORTEX_IMPORT_DIRECTORY . '/forms/Save.php';
        $form = new CortexImport_Form_Save(array(
            'collections' => $collectionNames,
            'itemTypeNames' => $itemTypeNames
        ));
        return $form;
    }

    protected $_pluginConfig = array();

    /**
     * Returns the plugin configuration
     *
     * @return array
     */
    protected function _getPluginConfig()
    {
        if (! $this->_pluginConfig) {
            $config = $this->getInvokeArg('bootstrap')->config->plugins;
            if ($config && isset($config->CsvImport)) {
                $this->_pluginConfig = $config->CsvImport->toArray();
            }
            if (! array_key_exists('fileDestination', $this->_pluginConfig)) {
                $this->_pluginConfig['fileDestination'] = Zend_Registry::get('storage')->getTempDir();
            }
        }
        return $this->_pluginConfig;
    }
}
