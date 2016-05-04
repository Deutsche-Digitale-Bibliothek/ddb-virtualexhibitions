<?php

/**
 * CortexImport_Item class - represents a Cortex Item
 *
 * @copyright 2013, Fraunhofer IAIS
 * @license http://www.apache.org/licenses/LICENSE-2.0.html Apache License V2
 * @package CortexImport
 */
class CortexImport_Item
{

    const DC_CONTRIBUTOR = 'contributor';

    const DC_COVERAGE = 'coverage';

    const DC_CREATOR = 'creator';

    const DC_DATE = 'date';

    const DC_DESCRIPTION = 'description';

    const DC_FORMAT = 'format';

    const DC_IDENTIFIER = 'identifier';

    const DC_LANGUAGE = 'language';

    const DC_PUBLISHER = 'publisher';

    const DC_RELATION = 'relation';

    const DC_RIGHTS = 'rights';

    const DC_SOURCE = 'source';

    const DC_SUBJECT = 'subject';

    const DC_TITLE = 'title';

    const DC_TYPE = 'type';

    private $_id = '';

    private $_xml = '';

    private $_xpaths = array(
        CortexImport_Item::DC_CONTRIBUTOR => array(
            '//ctx:edm/rdf:RDF/edm:ProvidedCHO/dc:contributor'
        ),
        CortexImport_Item::DC_COVERAGE => array(
            '//ctx:edm/rdf:RDF/edm:ProvidedCHO/dc:coverage',
            '//ctx:edm/rdf:RDF/edm:ProvidedCHO/edm:currentLocation'
        ),
        CortexImport_Item::DC_CREATOR => array(
            '//ctx:edm/rdf:RDF/edm:ProvidedCHO/dc:creator'
        ),
        CortexImport_Item::DC_DATE => array(
            '//ctx:edm/rdf:RDF/edm:ProvidedCHO/dc:date'
        ),
        CortexImport_Item::DC_DESCRIPTION => array(
            '//ctx:edm/rdf:RDF/edm:ProvidedCHO/dc:description'
        ),
        CortexImport_Item::DC_FORMAT => array(
            '//ctx:edm/rdf:RDF/edm:ProvidedCHO/dc:format'
        ),
        CortexImport_Item::DC_IDENTIFIER => array(
            '//ctx:edm/rdf:RDF/edm:ProvidedCHO/dc:identifier'
        ),
        CortexImport_Item::DC_LANGUAGE => array(
            '//ctx:edm/rdf:RDF/edm:ProvidedCHO/dc:language'
        ),
        CortexImport_Item::DC_PUBLISHER => array(
            '//ctx:edm/rdf:RDF/edm:ProvidedCHO/dc:publisher'
        ),
        CortexImport_Item::DC_RELATION => array(
            '//ctx:edm/rdf:RDF/edm:ProvidedCHO/dc:relation',
            '//ctx:edm/rdf:RDF/ore:Aggregation/edm:dataProvider'
        ),
        CortexImport_Item::DC_RIGHTS => array(
            '//ctx:edm/rdf:RDF/edm:ProvidedCHO/dc:rights'
        ),
        CortexImport_Item::DC_SOURCE => array(
            '//ctx:edm/rdf:RDF/edm:ProvidedCHO/dc:source'
        ),
        CortexImport_Item::DC_SUBJECT => array(
            '//ctx:edm/rdf:RDF/edm:ProvidedCHO/dc:subject'
        ),
        CortexImport_Item::DC_TITLE => array(
            '//ctx:edm/rdf:RDF/edm:ProvidedCHO/dc:title'
        ),
        CortexImport_Item::DC_TYPE => array(
            '//ctx:edm/rdf:RDF/edm:ProvidedCHO/dc:type'
        )
    );

    private static $_dc_fields = array(
        CortexImport_Item::DC_CONTRIBUTOR,
        CortexImport_Item::DC_COVERAGE,
        CortexImport_Item::DC_CREATOR,
        CortexImport_Item::DC_DATE,
        CortexImport_Item::DC_DESCRIPTION,
        CortexImport_Item::DC_FORMAT,
        CortexImport_Item::DC_IDENTIFIER,
        CortexImport_Item::DC_LANGUAGE,
        CortexImport_Item::DC_PUBLISHER,
        CortexImport_Item::DC_RELATION,
        CortexImport_Item::DC_RIGHTS,
        CortexImport_Item::DC_SOURCE,
        CortexImport_Item::DC_SUBJECT,
        CortexImport_Item::DC_TITLE,
        CortexImport_Item::DC_TYPE
    );

    private $_xpath_values = array();

    private $_CORTEX_NS = array(
        'ctx' => 'http://www.deutsche-digitale-bibliothek.de/cortex',
        'int' => 'http://www.deutsche-digitale-bibliothek.de/institution',
        'itm' => 'http://www.deutsche-digitale-bibliothek.de/item',
        'rdf' => 'http://www.w3.org/1999/02/22-rdf-syntax-ns#',
        'edm' => 'http://www.europeana.eu/schemas/edm/',
        'dc' => 'http://purl.org/dc/elements/1.1/',
        'ore' => 'http://www.openarchives.org/ore/terms/'
    );

    private $_is_public;

    private $_is_featured;

    private $_item_type_id;

    private $_collection_id;

    /**
     *
     * @param string $xml
     *            Xml content of a Cortex item.
     */
    public function __construct($xml)
    {
        $this->_xml = new SimpleXMLElement($xml);
        
        reset($this->_CORTEX_NS);
        while (list ($key, $val) = each($this->_CORTEX_NS)) {
            $this->_xml->registerXPathNamespace($key, $val);
        }
        
        if ($this->_xml->properties != null) {
            $this->_id = $this->_xml->properties->itemId;
        }
        
        reset($this->_xpaths);
        while (list ($dcElement, $xpaths) = each($this->_xpaths)) {
            error_log('Element ' . $dcElement . ' / Path ' . $xpaths);
            if (! empty($xpaths)) {
                $concatXPathValue = '';
                foreach ($xpaths as $index => $oneXPath) {
                    $xPathValues = $this->_xml->xpath($oneXPath);
                    if (is_array($xPathValues)) {
                        foreach ($xPathValues as $index => $oneXPathValue) {
                            if (! empty($oneXPathValue)) {
                                $concatXPathValue = $concatXPathValue . $oneXPathValue . "; ";
                            }
                        }
                    } else {
                        if (! empty($xPathValues)) {
                            $concatXPathValue = $concatXPathValue . $xPathValues . "; ";
                        }
                    }
                }
                
                $this->_xpath_values[$dcElement] = rtrim($concatXPathValue, "; ");
            }
        }
    }

    /**
     * Get the id of the item.
     *
     * @return string
     */
    public function getItemId()
    {
        return $this->_id;
    }

    /**
     * Get the xml as php array
     *
     * @return array
     */
    public function getXml()
    {
        return $this->_xml;
    }

    /**
     * Is the item public?
     *
     * @return boolean
     */
    public function isPublic()
    {
        return $this->_is_public;
    }

    /**
     * Is the item featured?
     *
     * @return boolean
     */
    public function isFeatured()
    {
        return $this->_is_featured;
    }

    /**
     * Get the collection id
     *
     * @return int
     */
    public function getCollectionId()
    {
        return $this->_collection_id;
    }

    /**
     * Get the item type id?
     *
     * @return int
     */
    public function getItemTypeId()
    {
        return $this->_item_type_id;
    }

    /**
     * Sets whether the imported items are public
     *
     * @param mixed $flag
     *            A boolean representation
     */
    public function setItemsArePublic($flag)
    {
        $booleanFilter = new Omeka_Filter_Boolean();
        $this->_is_public = $booleanFilter->filter($flag);
    }

    /**
     * Sets whether the imported items are featured
     *
     * @param mixed $flag
     *            A boolean representation
     */
    public function setItemsAreFeatured($flag)
    {
        $booleanFilter = new Omeka_Filter_Boolean();
        $this->_is_featured = $booleanFilter->filter($flag);
    }

    /**
     * Sets the collection id of the collection to which the imported items belong
     *
     * @param int $id
     *            The collection id
     */
    public function setCollectionId($id)
    {
        if (! $id) {
            $this->_collection_id = null;
        } else {
            $this->_collection_id = (int) $id;
        }
    }

    /**
     * Sets the item type id of the item type of every imported item
     *
     * @param int $id
     *            The item type id
     */
    public function setItemTypeId($id)
    {
        if (! $id) {
            $this->_item_type_id = null;
        } else {
            $this->_item_type_id = (int) $id;
        }
    }

    /**
     * Get a value from the item for a specific dc element name.
     *
     * @param string $dcField
     *            Name of the DC element.
     * @return string: Value of the item for the specific field.
     */
    public function getValueForDCField($dcField)
    {
        if (array_key_exists($dcField, $this->_xpath_values)) {
            return $this->_xpath_values[$dcField];
        }
        
        return '';
    }

    public function getAllFieldValues()
    {
        return $this->_xpath_values;
    }

    public static function getDCFieldNames()
    {
        return CortexImport_Item::$_dc_fields;
    }

    public function toString()
    {
        $output = '[';
        
        reset($this->_xpaths);
        while (list ($dcElement, $xpath) = each($this->_xpaths)) {
            if (array_key_exists($dcElement, $this->_xpath_values)) {
                $output = $output . $dcElement . '=' . $this->_xpath_values[$dcElement] . '\n\r';
            } else {
                $output = $output . $dcElement . '= undefined\n\r';
            }
        }
        
        return $output . ']';
    }
}