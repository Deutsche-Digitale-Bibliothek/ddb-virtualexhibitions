<?php
/**
 * Zlb Item Relations Story to Location
 * @copyright Copyright 2014 Viktor Grandgeorg
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 */

/**
 * Zlb Item Relations Story to Location model.
 */
class ExhibitColorScheme extends Omeka_Record_AbstractRecord implements Zend_Acl_Resource_Interface
{

    public $name;
    public $background;
    public $foreground;
    public $ctrl_background;
    public $ctrl_foreground;
    public $modified_by_user_id;
    public $created_by_user_id;
    public $updated;
    public $inserted;

    // protected function _initializeMixins()
    // {
    //     $this->_mixins[] = new Mixin_Search($this);
    // }

    public function getResourceId()
    {
        return 'ExhibitColorScheme';
    }

    public function getModifiedByUser()
    {
        return $this->getTable('User')->find($this->modified_by_user_id);
    }

    public function getCreatedByUser()
    {
        return $this->getTable('User')->find($this->created_by_user_id);
    }

    protected function _validate()
    {
        if (empty($this->name)) {
            $this->addError('name', __('Das Farbschema muss einen Namen haben.'));
        }

        if (255 < strlen($this->name)) {
            $this->addError('name', __('Der Name darf nicht länger als 255 Zeichen sein'));
        }

        if (!$this->fieldIsUnique('name')) {
            $this->addError('name', __('Einfarbschema mit diesem Namen existiert bereits, wählen Sie einen anderen Namen.'));
        }
    }

    protected function beforeSave($args)
    {
        $this->name = trim($this->name);
        $this->modified_by_user_id = current_user()->id;
        $this->updated = date('Y-m-d H:i:s');
    }

    public function getProperty($property)
    {
        switch($property) {
            case 'created_username':
                return $this->getCreatedByUser()->username;
            case 'modified_username':
                return $this->getModifiedByUser()->username;
            default:
                return parent::getProperty($property);
        }
    }
}
