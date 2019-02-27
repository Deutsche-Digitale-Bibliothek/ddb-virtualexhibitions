<?php
/**
 * ExhibitColorPalette table class.
 *
 * @package ExhibitBuilder
 */
class Table_ExhibitColorPalette extends Omeka_Db_Table
{

    public function getSelect()
    {
        $select = parent::getSelect();
        return $select;
    }

}
