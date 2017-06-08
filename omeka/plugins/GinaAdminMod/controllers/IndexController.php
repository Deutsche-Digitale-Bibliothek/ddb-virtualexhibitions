<?php
/**
 * Admin Mod
 *
 * @copyright Copyright 2017 Grandgeorg Websolutions
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 */

/**
 * The Admin Mod index controller class.
 *
 * @package GinaAdminMod
 */
class GinaAdminMod_IndexController extends Omeka_Controller_AbstractActionController
{
    public function init() {}

    public function indexAction()
    {
        $this->_helper->redirector('index', 'index', 'items');
        return;
    }

    public function duplicateitemAction()
    {
        $originalId = $this->_request->getParam('id');
        if (!isset($originalId) || empty($originalId) || 0 == (int) $originalId) {
            $this->_helper->flashMessenger(__('Wählen Sie ein Objekt, das sie duplizieren möchten, aus.'), 'error');
            $this->_helper->redirector('index', 'index', 'items');
        }
        $currentuser = Zend_Registry::get('bootstrap')->getResource('currentuser');

        $db = get_db();
        $itemTbl = $db->getTable('Item');
        $elementTextsTbl = $db->getTable('ElementText');

        $originalItem = $itemTbl->find((int) $originalId);
        // var_dump($originalItem->id, $originalItem->modified);
        $duplicteItem = clone $originalItem;
        $duplicteItem->owner_id = $currentuser->id;
        $duplicteItem->modified = date('Y-m-d H:i:s');
        $duplicteItem->added = date('Y-m-d H:i:s');
        $duplicteItem->save();

        $originalElementTexts = $elementTextsTbl->findByRecord($originalItem);
        if (isset($originalElementTexts) && !empty($originalElementTexts) && is_array($originalElementTexts)) {
            foreach ($originalElementTexts as $originalElementText) {
                $duplicateElementText = clone $originalElementText;
                $duplicateElementText->record_id = $duplicteItem->id;
                if ($duplicateElementText->element_id == 50 || $duplicateElementText->element_id == 52) {
                    $duplicateElementText->text = 'Kopie von ' . $duplicateElementText->text;
                }
                $duplicateElementText->save();
            }
        }

        $this->_helper->flashMessenger(__('Objekt erfolgreich dupliziert'));
        $this->_helper->redirector('index', 'index', 'items');
        return;
    }

}
