<?php
/**
 * ExhibitColorSchemeDesigner
 * @copyright Copyright 2016 Viktor Grandgeorg
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 */

require_once dirname(__FILE__) . '/../helpers/ColorHelper.php';

/**
 * index controller.
 *
 */
class ExhibitColorSchemeDesigner_IndexController extends
    Omeka_Controller_AbstractActionController
{
    public function init()
    {
        $this->_helper->db->setDefaultModelName('ExhibitColorScheme');
    }


    public function indexAction()
    {
        $this->_helper->redirector('browse');
        return;
    }

    public function browseAction()
    {
        // $this->_helper->redirector('browse');
        return;
    }

    public function addAction()
    {
        $model = new ExhibitColorScheme;
        $model->created_by_user_id = current_user()->id;
        $this->view->form = $this->_getForm($model);
        $this->_processForm($model, 'add');
    }

    public function editAction()
    {
        $model = $this->_helper->db->findById();
        $this->view->form = $this->_getForm($model);
        $this->_processForm($model, 'edit');
    }

    protected function _getForm($model = null)
    {
        $formOptions = array(
            'type' => 'exhibit_color_scheme',
            // 'hasPublicPage' => true
        );

        if ($model && $model->exists()) {
            $formOptions['record'] = $model;
        }

        $form = new Omeka_Form_Admin($formOptions);
        $form->addElementToEditGroup(
            'text', 'name',
            array(
                'id' => 'exhibit-color-scheme-name',
                'value' => $model->name,
                'label' => __('Name'),
                'description' => __('Name des Farbschema'),
                'required' => true
            )
        );

        $form->addElementToEditGroup(
            'text', 'background',
            array(
                'id' => 'exhibit-color-scheme-background',
                'value' => $model->background,
                'label' => __('Hintergrundfarbe'),
                'description' => __('Farbe des Hintergrundes als Hexadezimalwert<br>(Bsp. #f3de9c oder #333)'),
                'required' => true
            )
        );

        $form->addElementToEditGroup(
            'text', 'foreground',
            array(
                'id' => 'exhibit-color-scheme-foreground',
                'value' => $model->foreground,
                'label' => __('Vordergrundfarbe'),
                'description' => __('Vordergrund- bzw. Schriftfarbe als Hexadezimalwert<br>(Bsp. #f3de9c oder #333)'),
                'required' => true
            )
        );

        $form->addElementToEditGroup(
            'text', 'ctrl_background',
            array(
                'id' => 'exhibit-color-scheme-ctrl-background',
                'value' => $model->ctrl_background,
                'label' => __('Hintergrundfarbe der Steuerelemente'),
                'description' => __('Farbe des Hintergrundes als Hexadezimalwert<br>(Bsp. #f3de9c oder #333)'),
                'required' => true
            )
        );

        $form->addElementToEditGroup(
            'text', 'ctrl_foreground',
            array(
                'id' => 'exhibit-color-scheme-ctrl-foreground',
                'value' => $model->ctrl_foreground,
                'label' => __('Vordergrundfarbe der Steuerelemente'),
                'description' => __('Vordergrund- bzw. Schriftfarbe als Hexadezimalwert<br>(Bsp. #f3de9c oder #333)'),
                'required' => true
            )
        );

        return $form;
    }

    private function _processForm($model, $action)
    {
        if ($this->getRequest()->isPost()) {
            try {
                $model->setPostData($_POST);
                if ($model->save()) {
                    if ('add' == $action) {
                        $this->_helper->flashMessenger(__('Das Frabschema "%s" wurde erfolgreich hinzugefügt.', $model->name), 'success');
                    } else if ('edit' == $action) {
                        $this->_helper->flashMessenger(__('Das Frabschema "%s" wurde erfolgreich bearbeitet.', $model->name), 'success');
                    }
                    $this->writeColorSheme();
                    $this->_helper->redirector('browse');
                    return;
                }
            } catch (Omeka_Validate_Exception $e) {
                $this->_helper->flashMessenger($e);
            }
        }
        $this->view->exhibit_color_scheme = $model;
    }

    public function writeColorSheme()
    {
        $colorSchemes = get_db()->getTable('ExhibitColorScheme')->findAll();
        $css = ColorHelper::getColorShemeCss($colorSchemes);
        file_put_contents(dirname(dirname(__FILE__)) . '/css/colorschemes.css', $css);
    }

    protected function _getDeleteSuccessMessage($record)
    {
        return __('Das Frabschema "%s" wurde erfolgreich gelöscht.', $record->name);
    }
}
