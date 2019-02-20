<?php
/**
 * ExhibitColorScheme
 * @copyright Copyright 2016 Viktor Grandgeorg
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 */

/**
 * Zlb Item Relations Media to Story table.
 */
class ExhibitColorSchemeTable extends Omeka_Db_Table
{
    /**
     * Get the default select object.
     *
     * @return Omeka_Db_Select
     */
    public function getSelect()
    {
        $db = $this->getDb();
        return parent::getSelect();
    }

    /**
     * Retrieve a set of model objects based on a given number of parameters
     *
     * @uses Omeka_Db_Table::getSelectForFindBy()
     * @param array $params A set of parameters by which to filter the objects
     * that get returned from the database.
     * @param integer $limit Number of objects to return per "page".
     * @param integer $page Page to retrieve.
     * @return array|null The set of objects that is returned
     */
    public function findBy($params = array(), $limit = null, $page = null)
    {
        $user = current_user();
        $select = $this->getSelectForFindBy($params);
        if ($limit) {
            $this->applyPagination($select, $limit, $page);
        }
        return $this->fetchObjects($select);
    }
}
