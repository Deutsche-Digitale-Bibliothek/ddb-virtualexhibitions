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
class CortexImport_Form_Verify extends Omeka_Form
{

    private $_fieldValues;


    /**
     * Initialize the form.
     */
    public function init()
    {
        parent::init();
        $this->setAttrib('id', 'corteximport-verify');
        $this->setMethod('post');
                
        foreach (CortexImport_Item::getDCFieldNames() as $index => $dcField) {
            $fieldDescription = 'Value of Field ' . ucfirst($dcField);

            $fieldValue = '';
            if (!empty($this->_fieldValues[$dcField])) {
                $fieldValue = $this->_fieldValues[$dcField];
            }
            
            $this->addElement('textarea', $dcField, array(
                'label' => ucfirst($dcField),
                'value' => $fieldValue,
                'required' => false,
                'cols' => '150',
                'rows' => '4',
                'validators' => array(),
            )); 
        }

        $submit = $this->createElement('submit', 'submit', array(
        		'label' => 'Next',
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

    public function setFieldValues($fieldValues)
    {
        $this->_fieldValues = $fieldValues;
    }
}
