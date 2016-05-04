<?php

/**
 * CortexImport_Form_Verify class - represents the form on cortex-import/index/verify.
 *
 * Adaption of CsvImport_Form_Mapping Class.
 *
 * @copyright Copyright 2007-2012 Roy Rosenzweig Center for History and New Media
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 * @package CortexImport
 */
class CortexImport_Form_Save extends Omeka_Form
{

    private $_collections = array();

    private $_itemTypeNames = array();

    /**
     * Initialize the form.
     */
    public function init()
    {
        parent::init();
        $this->setAttrib('id', 'corteximport-save');
        $this->setMethod('post');
        
        $this->addElement('select', 'collection_id', array(
            'label' => 'Select a collection',
            'multiOptions' => $this->_collections,
            'required' => true,
            'isArray' => false,
            'validators' => array()
        ));
        
        $this->addElement('select', 'item_type_id', array(
            'label' => 'Select item type',
            'multiOptions' => $this->_itemTypeNames,
            'required' => true,
            'isArray' => false,
            'validators' => array()
        ));
        
        $this->addElement('checkbox', 'is_featured', array(
            'label' => 'Is featured?',
            'value' => false,
            'required' => true,
            'validators' => array()
        ));
        
        $this->addElement('checkbox', 'is_public', array(
            'label' => 'Is public?',
            'value' => false,
            'required' => true,
            'validators' => array()
        ));
        
        $this->addElement('text', 'tags', array(
            'label' => 'Enter keyword tags',
            'value' => '',
            'required' => false,
            'size' => '150',
            'validators' => array()
        ));
        
        $submit = $this->createElement('submit', 'submit', array(
            'label' => 'Import',
            'class' => 'submit submit-medium'
        ));
        
        $submit->setDecorators(array(
            'ViewHelper',
            array(
                'HtmlTag',
                array(
                    'tag' => 'div',
                    'class' => 'csvimportnext'
                )
            )
        ));
        
        $this->addElement($submit);
    }

    public function setCollections($collections)
    {
        $this->_collections = $collections;
    }

    public function setItemTypeNames($itemTypeNames)
    {
        $this->_itemTypeNames = $itemTypeNames;
    }
}
