<?php

require_once dirname(__FILE__) . '/Color.php';

use Mexitek\PHPColors\Color;
/**
* Color Helper Class
*/
class ColorHelper
{

    public static function getFullHex($hex)
    {
        if(strlen($hex) === 4) {
            $hex = '#'
                . substr($hex, 1, 1) . substr($hex, 1, 1)
                . substr($hex, 2, 1) . substr($hex, 2, 1)
                . substr($hex, 3, 1) . substr($hex, 3, 1);
        }
        return $hex;
    }

    /**
     * @return array array RGB-keys
     */
    public static function getRgbFromHex($hex)
    {
        $rgb = array();
        $rgb['r'] = hexdec(substr($hex, 1, 2));
        $rgb['g'] = hexdec(substr($hex, 3, 2));
        $rgb['b'] = hexdec(substr($hex, 5, 2));

        return $rgb;
    }

     /**
     * @param object Omeka DB object with current color sheme definitions
     * @return string color shemes css definitions
     */
    public static function getColorShemeCss($colorSchemes)
    {
        $css = '';
        if (isset($colorSchemes) && !empty($colorSchemes)) {
            foreach ($colorSchemes as $colorScheme) {

                $colorSchemeBackground = self::getFullHex($colorScheme->background);
                $colorSchemeBackgroundRgb = self::getRgbFromHex($colorSchemeBackground);
                $colorSchemeForeground = self::getFullHex($colorScheme->foreground);

                $colorSchemeCtrlBackground = self::getFullHex($colorScheme->ctrl_background);
                $colorSchemeCtrlBackgroundRgb = self::getRgbFromHex($colorSchemeCtrlBackground);
                $colorSchemeCtrlForeground = self::getFullHex($colorScheme->ctrl_foreground);
                $colorSchemeCtrlForegroundRgb = self::getRgbFromHex($colorSchemeCtrlForeground);

                $colorCtrlBackground = new Color($colorSchemeCtrlBackground);

                if ($colorCtrlBackground->isDark()) {
                    $topNavBgRgb['r'] = (($colorSchemeCtrlBackgroundRgb['r'] - 30) >= 0)? $colorSchemeCtrlBackgroundRgb['r'] - 30 : 0;
                    $topNavBgRgb['g'] = (($colorSchemeCtrlBackgroundRgb['g'] - 30) >= 0)? $colorSchemeCtrlBackgroundRgb['g'] - 30 : 0;
                    $topNavBgRgb['b'] = (($colorSchemeCtrlBackgroundRgb['b'] - 30) >= 0)? $colorSchemeCtrlBackgroundRgb['b'] - 30 : 0;

                    $sideNavBgRgb['r'] = (($colorSchemeCtrlBackgroundRgb['r'] - 51) >= 0)? $colorSchemeCtrlBackgroundRgb['r'] - 51 : 0;
                    $sideNavBgRgb['g'] = (($colorSchemeCtrlBackgroundRgb['g'] - 51) >= 0)? $colorSchemeCtrlBackgroundRgb['g'] - 51 : 0;
                    $sideNavBgRgb['b'] = (($colorSchemeCtrlBackgroundRgb['b'] - 51) >= 0)? $colorSchemeCtrlBackgroundRgb['b'] - 51 : 0;

                    $listGroupItemBgRgb['r'] = (($colorSchemeCtrlBackgroundRgb['r'] + 10) <= 255)? $colorSchemeCtrlBackgroundRgb['r'] + 10 : 255;
                    $listGroupItemBgRgb['g'] = (($colorSchemeCtrlBackgroundRgb['g'] + 10) <= 255)? $colorSchemeCtrlBackgroundRgb['g'] + 10 : 255;
                    $listGroupItemBgRgb['b'] = (($colorSchemeCtrlBackgroundRgb['b'] + 10) <= 255)? $colorSchemeCtrlBackgroundRgb['b'] + 10 : 255;

                    $listGroupItemBorderRgb['r'] = (($colorSchemeCtrlBackgroundRgb['r'] + 19) <= 255)? $colorSchemeCtrlBackgroundRgb['r'] + 19 : 255;
                    $listGroupItemBorderRgb['g'] = (($colorSchemeCtrlBackgroundRgb['g'] + 19) <= 255)? $colorSchemeCtrlBackgroundRgb['g'] + 19 : 255;
                    $listGroupItemBorderRgb['b'] = (($colorSchemeCtrlBackgroundRgb['b'] + 19) <= 255)? $colorSchemeCtrlBackgroundRgb['b'] + 19 : 255;

                    $listGroupItemHoverBgRgb['r'] = (($colorSchemeCtrlBackgroundRgb['r'] - 15) >= 0)? $colorSchemeCtrlBackgroundRgb['r'] - 15 : 0;
                    $listGroupItemHoverBgRgb['g'] = (($colorSchemeCtrlBackgroundRgb['g'] - 15) >= 0)? $colorSchemeCtrlBackgroundRgb['g'] - 15 : 0;
                    $listGroupItemHoverBgRgb['b'] = (($colorSchemeCtrlBackgroundRgb['b'] - 15) >= 0)? $colorSchemeCtrlBackgroundRgb['b'] - 15 : 0;

                    $listGroupItemHoverBorderRgb['r'] = (($colorSchemeCtrlBackgroundRgb['r'] + 4) <= 255)? $colorSchemeCtrlBackgroundRgb['r'] + 4 : 255;
                    $listGroupItemHoverBorderRgb['g'] = (($colorSchemeCtrlBackgroundRgb['g'] + 4) <= 255)? $colorSchemeCtrlBackgroundRgb['g'] + 4 : 255;
                    $listGroupItemHoverBorderRgb['b'] = (($colorSchemeCtrlBackgroundRgb['b'] + 4) <= 255)? $colorSchemeCtrlBackgroundRgb['b'] + 4 : 255;

                    $navPillsItemActiveBgRgb['r'] = (($colorSchemeCtrlBackgroundRgb['r'] + 64) <= 255)? $colorSchemeCtrlBackgroundRgb['r'] + 64 : 255;
                    $navPillsItemActiveBgRgb['g'] = (($colorSchemeCtrlBackgroundRgb['g'] + 64) <= 255)? $colorSchemeCtrlBackgroundRgb['g'] + 64 : 255;
                    $navPillsItemActiveBgRgb['b'] = (($colorSchemeCtrlBackgroundRgb['b'] + 64) <= 255)? $colorSchemeCtrlBackgroundRgb['b'] + 64 : 255;

                    $navPillsItemActiveRgb['r'] = $colorSchemeCtrlForegroundRgb['r'];
                    $navPillsItemActiveRgb['g'] = $colorSchemeCtrlForegroundRgb['g'];
                    $navPillsItemActiveRgb['b'] = $colorSchemeCtrlForegroundRgb['b'];

                } else {

                    $topNavBgRgb['r'] = (($colorSchemeCtrlBackgroundRgb['r'] + 30) <= 255)? $colorSchemeCtrlBackgroundRgb['r'] + 30 : 255;
                    $topNavBgRgb['g'] = (($colorSchemeCtrlBackgroundRgb['g'] + 30) <= 255)? $colorSchemeCtrlBackgroundRgb['g'] + 30 : 255;
                    $topNavBgRgb['b'] = (($colorSchemeCtrlBackgroundRgb['b'] + 30) <= 255)? $colorSchemeCtrlBackgroundRgb['b'] + 30 : 255;

                    $sideNavBgRgb['r'] = (($colorSchemeCtrlBackgroundRgb['r'] + 51) <= 255)? $colorSchemeCtrlBackgroundRgb['r'] + 51 : 255;
                    $sideNavBgRgb['g'] = (($colorSchemeCtrlBackgroundRgb['g'] + 51) <= 255)? $colorSchemeCtrlBackgroundRgb['g'] + 51 : 255;
                    $sideNavBgRgb['b'] = (($colorSchemeCtrlBackgroundRgb['b'] + 51) <= 255)? $colorSchemeCtrlBackgroundRgb['b'] + 51 : 255;

                    $listGroupItemBgRgb['r'] = (($colorSchemeCtrlBackgroundRgb['r'] - 21) >= 0)? $colorSchemeCtrlBackgroundRgb['r'] - 21 : 0;
                    $listGroupItemBgRgb['g'] = (($colorSchemeCtrlBackgroundRgb['g'] - 21) >= 0)? $colorSchemeCtrlBackgroundRgb['g'] - 21 : 0;
                    $listGroupItemBgRgb['b'] = (($colorSchemeCtrlBackgroundRgb['b'] - 21) >= 0)? $colorSchemeCtrlBackgroundRgb['b'] - 21 : 0;

                    $listGroupItemBorderRgb['r'] = (($colorSchemeCtrlBackgroundRgb['r'] - 19) >= 0)? $colorSchemeCtrlBackgroundRgb['r'] - 19 : 0;
                    $listGroupItemBorderRgb['g'] = (($colorSchemeCtrlBackgroundRgb['g'] - 19) >= 0)? $colorSchemeCtrlBackgroundRgb['g'] - 19 : 0;
                    $listGroupItemBorderRgb['b'] = (($colorSchemeCtrlBackgroundRgb['b'] - 19) >= 0)? $colorSchemeCtrlBackgroundRgb['b'] - 19 : 0;

                    $listGroupItemHoverBgRgb['r'] = (($colorSchemeCtrlBackgroundRgb['r'] + 15) <= 255)? $colorSchemeCtrlBackgroundRgb['r'] + 15 : 255;
                    $listGroupItemHoverBgRgb['g'] = (($colorSchemeCtrlBackgroundRgb['g'] + 15) <= 255)? $colorSchemeCtrlBackgroundRgb['g'] + 15 : 255;
                    $listGroupItemHoverBgRgb['b'] = (($colorSchemeCtrlBackgroundRgb['b'] + 15) <= 255)? $colorSchemeCtrlBackgroundRgb['b'] + 15 : 255;

                    $listGroupItemHoverBorderRgb['r'] = (($colorSchemeCtrlBackgroundRgb['r'] - 4) >= 0)? $colorSchemeCtrlBackgroundRgb['r'] - 4 : 0;
                    $listGroupItemHoverBorderRgb['g'] = (($colorSchemeCtrlBackgroundRgb['g'] - 4) >= 0)? $colorSchemeCtrlBackgroundRgb['g'] - 4 : 0;
                    $listGroupItemHoverBorderRgb['b'] = (($colorSchemeCtrlBackgroundRgb['b'] - 4) >= 0)? $colorSchemeCtrlBackgroundRgb['b'] - 4 : 0;

                    $navPillsItemActiveBgRgb['r'] = (($colorSchemeCtrlBackgroundRgb['r'] - 128) >= 0)? $colorSchemeCtrlBackgroundRgb['r'] - 128 : 0;
                    $navPillsItemActiveBgRgb['g'] = (($colorSchemeCtrlBackgroundRgb['g'] - 128) >= 0)? $colorSchemeCtrlBackgroundRgb['g'] - 128 : 0;
                    $navPillsItemActiveBgRgb['b'] = (($colorSchemeCtrlBackgroundRgb['b'] - 128) >= 0)? $colorSchemeCtrlBackgroundRgb['b'] - 128 : 0;

                    $navPillsItemActiveRgb['r'] = 255;
                    $navPillsItemActiveRgb['g'] = 255;
                    $navPillsItemActiveRgb['b'] = 255;

                }

                $css .= '
                    .fullpage-wrapper .colorscheme_' . $colorScheme->id . ' {
                        background-color: ' . $colorSchemeBackground . ';
                        color: ' .$colorSchemeForeground . ';
                    }
                    .fullpage-wrapper .colorscheme_' . $colorScheme->id . ' .bg_content {
                        background-color: rgba('
                            . $colorSchemeBackgroundRgb['r'] . ', '
                            . $colorSchemeBackgroundRgb['g'] . ', '
                            . $colorSchemeBackgroundRgb['b'] . ', 0.8);
                    }
                    .ctrl_colorscheme_' . $colorScheme->id . ' a {
                        color: ' .$colorSchemeForeground . ';
                    }

                    .ctrl_colorscheme_' . $colorScheme->id . ' .top-nav,
                    .ctrl_colorscheme_' . $colorScheme->id . ' .top-nav a,
                    .ctrl_colorscheme_' . $colorScheme->id . ' .top-nav a:hover {
                        color: ' . $colorSchemeCtrlForeground . ';
                    }
                    .ctrl_colorscheme_' . $colorScheme->id . ' .top-nav {
                        background-color: rgba('
                            . $topNavBgRgb['r'] . ', '
                            . $topNavBgRgb['g'] . ', '
                            . $topNavBgRgb['b'] . ', 0.8);
                    }

                    .ctrl_colorscheme_' . $colorScheme->id . ' .sidenav-main,
                    .ctrl_colorscheme_' . $colorScheme->id . ' .sidenav-main a,
                    .ctrl_colorscheme_' . $colorScheme->id . ' .sidenav-main .list-group-item,
                    .ctrl_colorscheme_' . $colorScheme->id . ' .sidenav-main .list-group-item a,
                    .ctrl_colorscheme_' . $colorScheme->id . ' .sidenav-main .list-group-item a:hover,
                    .ctrl_colorscheme_' . $colorScheme->id . ' .sidenav-main a.list-group-item .list-group-item-heading {
                        color: ' . $colorSchemeCtrlForeground . ';
                    }
                    .ctrl_colorscheme_' . $colorScheme->id . ' .sidenav-main {
                        background-color: rgba('
                            . $sideNavBgRgb['r'] . ', '
                            . $sideNavBgRgb['g'] . ', '
                            . $sideNavBgRgb['b'] . ', 0.9);
                    }

                    .ctrl_colorscheme_' . $colorScheme->id . ' .sidenav-main a:hover,
                    .ctrl_colorscheme_' . $colorScheme->id . ' .sidenav-main a:focus,
                    .ctrl_colorscheme_' . $colorScheme->id . ' .sidenav-main .list-group-item {
                        background-color: rgba('
                            . $listGroupItemBgRgb['r'] . ', '
                            . $listGroupItemBgRgb['g'] . ', '
                            . $listGroupItemBgRgb['b'] . ', 1);
                        border-color: rgba('
                            . $listGroupItemBorderRgb['r'] . ', '
                            . $listGroupItemBorderRgb['g'] . ', '
                            . $listGroupItemBorderRgb['b'] . ', 1);
                    }

                    .ctrl_colorscheme_' . $colorScheme->id . ' .sidenav-main a.list-group-item:hover {
                        background-color: rgba('
                            . $listGroupItemHoverBgRgb['r'] . ', '
                            . $listGroupItemHoverBgRgb['g'] . ', '
                            . $listGroupItemHoverBgRgb['b'] . ', 1);
                        border-color: rgba('
                            . $listGroupItemHoverBorderRgb['r'] . ', '
                            . $listGroupItemHoverBorderRgb['g'] . ', '
                            . $listGroupItemHoverBorderRgb['b'] . ', 1);
                    }
                    .ctrl_colorscheme_' . $colorScheme->id . ' .sidenav-main .nav-pills > li.active > a,
                    .ctrl_colorscheme_' . $colorScheme->id . ' .sidenav-main .nav-pills > li.active > a:hover,
                    .ctrl_colorscheme_' . $colorScheme->id . ' .sidenav-main .nav-pills > li.active > a:focus,
                    .ctrl_colorscheme_' . $colorScheme->id . ' .sidenav-main .nav-pills.nav > li > a:hover,
                    .ctrl_colorscheme_' . $colorScheme->id . ' .sidenav-main .nav-pills.nav > li > a:focus {
                        background-color: rgba('
                            . $navPillsItemActiveBgRgb['r'] . ', '
                            . $navPillsItemActiveBgRgb['g'] . ', '
                            . $navPillsItemActiveBgRgb['b'] . ', 1);
                        color: rgb('
                            . $navPillsItemActiveRgb['r'] . ', '
                            . $navPillsItemActiveRgb['g'] . ', '
                            . $navPillsItemActiveRgb['b'] . ');
                    }

                    .ctrl_colorscheme_' . $colorScheme->id . ' .prevnext,
                    .ctrl_colorscheme_' . $colorScheme->id . ' .prevnext a,
                    .ctrl_colorscheme_' . $colorScheme->id . ' .prevnext a:hover {
                        color: ' . $colorSchemeCtrlForeground . ';
                    }
                    .ctrl_colorscheme_' . $colorScheme->id . ' .prevnext {
                        background-color: rgba('
                            . $colorSchemeCtrlBackgroundRgb['r'] . ', '
                            . $colorSchemeCtrlBackgroundRgb['g'] . ', '
                            . $colorSchemeCtrlBackgroundRgb['b'] . ', 0.9);
                    }

                    .ctrl_colorscheme_' . $colorScheme->id . ' .infonav,
                    .ctrl_colorscheme_' . $colorScheme->id . ' .infonav a,
                    .ctrl_colorscheme_' . $colorScheme->id . ' .infonav a:hover,
                    .ctrl_colorscheme_' . $colorScheme->id . ' .item_description,
                    .ctrl_colorscheme_' . $colorScheme->id . ' .item_description a,
                    .ctrl_colorscheme_' . $colorScheme->id . ' .item_description a:hover,
                    .ctrl_colorscheme_' . $colorScheme->id . ' .item_description_shadowmask,
                    .ctrl_colorscheme_' . $colorScheme->id . ' .item_description_icon {
                        color: ' . $colorSchemeCtrlForeground . ';
                    }
                    .ctrl_colorscheme_' . $colorScheme->id . ' .infonav,
                    .ctrl_colorscheme_' . $colorScheme->id . ' .item_description,
                    .ctrl_colorscheme_' . $colorScheme->id . ' .item_description_shadowmask,
                    .ctrl_colorscheme_' . $colorScheme->id . ' .item_description_icon {
                        background-color: rgba('
                            . $colorSchemeCtrlBackgroundRgb['r'] . ', '
                            . $colorSchemeCtrlBackgroundRgb['g'] . ', '
                            . $colorSchemeCtrlBackgroundRgb['b'] . ', 1);
                    }
                    '
                    . "\n";
            }
        }
        // return $css;
        return preg_replace('/\s{2,}/',' ', $css);
    }
}
?>