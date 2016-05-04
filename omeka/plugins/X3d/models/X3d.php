<?php
/**
 * X3d
 * @copyright Copyright 2015 Viktor Grandgeorg
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 */

/**
 * X3d model.
 */
class X3d extends Omeka_Record_AbstractRecord implements Zend_Acl_Resource_Interface
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var int
     */
    public $item_id;

    /**
     * @var strg
     */
    public $directory;

    /**
     * @var strg
     */
    public $x3d_file;

    /**
     * @var strg
     */
    public $texture_file;

    /**
     * @var strg
     */
    public $thumbnail;


    public function getResourceId()
    {
        return 'X3d';
    }

}
