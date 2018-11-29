<?php
/**
 * AdminThemeHelper
 *
 * Helper methods for admin theme
 *
 * @author Grandgeorg Websolutions
 * @copyright Grandgeorg Websolutions 2017
 * @package omeka/admin/theme
 *
 */

/**
 * AdminThemeHelper
 */
class AdminThemeHelper
{

    static function getAllUsers()
    {
        return get_db()->getTable('User')->findAll();
    }

    static function findOwnerInUsers($ownerId, $users)
    {
        if (is_array($users)) {
            foreach ($users as $user) {
                if ($user->id === $ownerId) {
                    return $user;
                }
            }
        }
        return false;
    }

    static function findItemInExhibitPage($itemId)
    {
        return ExhibitDdbHelper::findItemInExhibitPage($itemId);
    }
}
