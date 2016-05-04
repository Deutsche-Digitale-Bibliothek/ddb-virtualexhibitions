<?php
/**
 * X3d
 * @copyright Copyright 2014 Viktor Grandgeorg
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 */

/**
 * X3d table.
 */
class Table_X3d extends Omeka_Db_Table
{
    /**
     * Get the default select object.
     *
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
        $db = $this->_db;
        $user = current_user();
        $publicOnly = false;
        if (!isset($user)) {
            $publicOnly = true;
        }
        $select = $this->getSelectForFindBy($params);
        if ($publicOnly === true) {
            $db = $this->getDb();
            $select
                ->join(array('item' => $db->Items), $db->item . '.id = ' . $db->X3d . '.item_id', array())
                ->where('item.public = ?', 1)
            ;
        }
        if ($limit) {
            $this->applyPagination($select, $limit, $page);
        }
        return $this->fetchObjects($select);
    }

    /**
     * Find item relations by item ID.
     *
     * @param int $itemId
     * @param bool $publicOnly
     * @return array
     */
    public function findByItemId($itemId, $publicOnly = false)
    {
        $db = $this->_db;
        $select = $this->getSelect()
            ->where('`item_id`=?', (int) $itemId);

        if ($publicOnly !== true) {
            $user = current_user();
            if (!isset($user)) {
                $publicOnly = true;
            }
        }
        if ($publicOnly !== false) {
            $select
                ->join(array('item' => $db->Items), 'item.id = x3ds.item_id', array())
                ->where('item.public = ?', 1)
            ;
        }
        return $this->fetchObject($select);
    }

    public function foo()
    {

    }
}
