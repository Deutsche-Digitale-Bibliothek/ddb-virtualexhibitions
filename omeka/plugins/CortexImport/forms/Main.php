<?php

/**
 * CortexImport_Form_Main class - represents the form on cortex-import/index/index.
 * 
 * Adaption from CsvImport plugin.
 *
 * @copyright Copyright 2007-2012 Roy Rosenzweig Center for History and New Media, 2013 Fraunhofer IAIS 
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 * @package CortexImport
 */
class CortexImport_Form_Main extends Omeka_Form
{

    private $_url;
    private $_key;
    private $_itemId;
    
    /**
     * Initialize the form.
     */
    public function init()
    {
        parent::init();
        
        $this->_createHostElement();
        $this->_createIdElement();
        $this->_createAPIKeyElement();
        
        $this->setAttrib('id', 'corteximport');
        $this->setMethod('post');
 
        $this->applyOmekaStyles();
        $this->setAutoApplyOmekaStyles(false);
        
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

    protected function _createHostElement()
    {
        $this->addElement('text', 'host_url', array(
            'label' => 'Enter a DDB Host URL',
            'description' => 'An URL pointing to the DDB instance available in your network.',
            'value' => $this->_url,
            'required' => true,
            'size' => '150',
            'validators' => array(
            		array(
            				'validator' => 'NotEmpty',
            				'breakChainOnFailure' => true,
            				'options' => array(
            						'messages' => array(
            								Zend_Validate_NotEmpty::IS_EMPTY => 'The host URL must not be empty.'
            						)
            				)
            		)
            )
        ));
    }
    
    protected function _createAPIKeyElement()
    {
    	$this->addElement('text', 'api_key', array(
    			'label' => 'Enter your API key',
    			'description' => 'Your private API Key to access the DDB API.',
    			'value' => $this->_key,
    			'required' => true,
    			'size' => '150',
    			'validators' => array()
    	));
    }

    protected function _createIdElement()
    {
        $this->addElement('text', 'cortex_id', array(
            'label' => 'Enter a DDB Item ID',
            'description' => 'A DDB Item Identifier available in the DDB.',
            'value' => $this->_itemId,
            'required' => true,
            'size' => '150',
            'validators' => array(
                array(
                    'validator' => 'NotEmpty',
                    'breakChainOnFailure' => true,
                    'options' => array(
                        'messages' => array(
                            Zend_Validate_NotEmpty::IS_EMPTY => 'The item ID must not be empty.'
                        )
                    )
                ),
                array(
                    'validator' => 'StringLength',
                    'options' => array(
                        'min' => 32,
                        'max' => 32,
                        'messages' => array(
                            Zend_Validate_StringLength::TOO_SHORT => 'A valid DDB item id must contain 32 characters.',
                            Zend_Validate_StringLength::TOO_LONG => 'A valid DDB item id must contain 32 characters.'
                        )
                    )
                )
            )
        ));
    }
    
    /**
     * Set the host url
     *
     * @param string API host url.
     */
    public function setUrl($url)
    {
    	$this->_url = $url;
    }
    
    /**
     * Set the api key to access the host.
     *
     * @param string API key.
     */
    public function setKey($apiKey)
    {
    	$this->_key = $apiKey;
    }
    
    /**
     * Set the DDB item id
     *
     * @param string DDB item id.
     */
    public function setItemId($ddbItemId)
    {
    	$this->_itemId = $ddbItemId;
    }
}
