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

    static function getExhibitType()
    {
        $db = get_db();
        $bar = get_db()->getTable('Exhibit')->findAll();
        if (!is_array($bar) || !isset($bar[0]) || !property_exists($bar[0], 'exhibit_type') || !isset($bar{0}->exhibit_type) || empty($bar{0}->exhibit_type)) {
            return 'leporello';
        } else {
            return $bar[0]->exhibit_type;
        }
    }
}
