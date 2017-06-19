<?php
/**
 * Omeka Exhibit Extension and Helper for DDB - Deutsche Digitale Bibliothek
 *
 * @copyright Copyright 2014 Viktor Grandgeorg, Grandgeorg Websolutions
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 * @author Viktor Grandgeorg, viktor@grandgeorg.de
 */

/**
 * ExhibitDdbHelper
 *
 * Helper class for displaying contents in exhibitions with static methods and memebers.
 * Methods are called from functions in ExhibitFunctions.php
 * (which are called from the templates) or directly from the templates
 * e.g. in exhibits/item.php
 *
 * @package Omeka\Plugins\ExhibitBuilder
 */
class ExhibitDdbHelper
{

    /**
     * @var array Info from Vimeo API for video files (width, height, etc.)
     */
    public static $videoVimeoInfo = array();

    /**
     * @var array Info for DDB video (for JW-Player) (width, height)
     */
    public static $videoDdbInfo = array();

    /**
     * @var int Counter for DDB Video ID
     */
    public static $videoDdbCount = 0;


    public static $elementsVersion = null;

    /**
     * Main shortcode parser
     *
     * @param string $subject String to parse for shortcodes
     * @return array shortcode matches
     */
    public static function parseShortcode($subject)
    {
        preg_match_all('|(\[\[)([^\]\:]+):([^\]]+)(\]\])|', $subject, $matches, PREG_SET_ORDER);
        return $matches;
    }

    /**
     * Get HTML markup for thumbnails of videos for thumbnail gallery based templates
     *
     * @param string $metaDataVideoSource
     * @param string $thumbnailsize
     * @return string
     */
    public static function getVideoThumbnailFromShortcode($metaDataVideoSource, $thumbnailsize = 'medium')
    {
        $output = '';
        $matches = self::parseShortcode($metaDataVideoSource);
        $videoType = self::getVideotypeFromShortcode($matches);
        if ($videoType !== 'none') {
            list(, $videoId) = explode(":", $matches[0][3]);
            switch ($videoType) {
                case 'vimeo':
                    self::setVideoVimeoInfo($videoId);
                    $videoInfo = self::getVideoVimeoInfo($videoId);
                    $currentThumbnailsize = 'thumbnail_' . $thumbnailsize;
                    if (isset($videoInfo[0][$currentThumbnailsize]) && !empty($videoInfo[0][$currentThumbnailsize])) {
                        $output = '<div class="external-thumbnail" style="background-image:url(\''
                            . $videoInfo[0][$currentThumbnailsize] . '\');"><img src="'
                            . img('thnplaceholder.gif') . '" alt="video" style="visibility:hidden;">'
                            . '<div class="blurb">Video</div></div>';
                    }
                    break;
                case 'ddb':
                    self::setVideoDdbInfo($videoId);
                    // get $videoId, $offsetStart, $offsetStop:
                    $extended = self::getDdbVideoTimeOffset($videoId);
                    extract($extended, EXTR_OVERWRITE);
                    $output = '<div class="external-thumbnail" style="background-image:url(\'http://www.deutsche-digitale-bibliothek.de/binary/'
                            . $videoId . '/mvpr/1.jpg\');"><img src="'
                            . img('thnplaceholder.gif') . '" alt="video" style="visibility:hidden;">'
                            . '<div class="blurb">Video</div></div>';
                    break;
                default:
                    break;
            }
        }
        return $output;
    }

    /**
     * Get HTML markup for images of videos for fullsize image based templates
     *
     * @param string $metaDataVideoSource
     * @param string $thumbnailsize
     * @return string
     */
    public static function getVideoThumbnailFromShortcodeForMainItem($metaDataVideoSource, $thumbnailsize = 'large')
    {
        $output = '';
        $matches = self::parseShortcode($metaDataVideoSource);
        $videoType = self::getVideotypeFromShortcode($matches);
        if ($videoType !== 'none') {
            list(, $videoId) = explode(":", $matches[0][3]);
            switch ($videoType) {
                case 'vimeo':
                    self::setVideoVimeoInfo($videoId);
                    $videoInfo = self::getVideoVimeoInfo($videoId);
                    $currentThumbnailsize = 'thumbnail_' . $thumbnailsize;
                    if (isset($videoInfo[0][$currentThumbnailsize]) && !empty($videoInfo[0][$currentThumbnailsize])) {
                        $output = '<img src="'
                            . $videoInfo[0][$currentThumbnailsize] . '" alt="video" >';
                    }
                    break;
                case 'ddb':
                    self::setVideoDdbInfo($videoId);
                    // get $videoId, $offsetStart, $offsetStop:
                    $extended = self::getDdbVideoTimeOffset($videoId);
                    extract($extended, EXTR_OVERWRITE);
                    $output = '<img src="http://www.deutsche-digitale-bibliothek.de/binary/'
                            . $videoId . '/mvpr/1.jpg" alt="video" >';
                    break;
                default:
                    break;
            }
        }
        return $output;
    }

    /**
     * Get the type of a video (vimeo, ddb, youtube, ...) from the shortcode
     *
     * @param satring|array $shortcode Videoshortcode
     * @return string Videotype
     */
    public static function getVideotypeFromShortcode($shortcode)
    {
        $type = 'none';
        if (is_string($shortcode)) {
            $shortcode = self::parseShortcode($shortcode);
        }
        if (is_array($shortcode) &&
            isset($shortcode[0][2]) &&
            'video' == $shortcode[0][2] &&
            isset($shortcode[0][3])) {
                list($type) = explode(":", $shortcode[0][3]);
        }
        return $type;
    }

    /**
     * Get offset times i.e. start and stop times for ddb type videos
     *
     * @param string $videoId Videoid from Shortcode
     * @return array Array with the id and the offsets. Arraykeys are 'videoId', 'offset-start', 'offset-stop'
     */
    public static function getDdbVideoTimeOffset($videoId)
    {
        $extended = array(
            'videoId' => $videoId,
            'offsetStart' => null,
            'offsetStop' => null
        );
        preg_match('/([a-zA-Z0-9]*)(-t=[0-9]*)([-0-9]*)/', $videoId, $matchOffset);
        if (!empty($matchOffset[1]) && !empty($matchOffset[2])) {
            $extended['videoId'] = $matchOffset[1];
            $extended['offsetStart'] = substr($matchOffset[2], 3);
        }
        if (!empty($matchOffset[3])) {
            $extended['offsetStop'] = substr($matchOffset[3], 1);
        }
        return $extended;
    }

    /**
     * Get HTML markup for the different video types (vimeo, dbb, ...)
     *
     * @param String $metaDataVideoSource Shortcode from item meta data
     * @param String $videoImage Alternative video image to display as start image for DBB videos
     * @return String Videoplayer HTML and Javascript
     */
    public static function getVideoFromShortcode($metaDataVideoSource, $videoImage = '')
    {
        $output = '';
        $matches = self::parseShortcode($metaDataVideoSource);
        if (isset($matches[0][2]) && 'video' == $matches[0][2] && isset($matches[0][3])) {
            list($videoType, $videoId) = explode(":", $matches[0][3]);
            switch ($videoType) {
                case 'vimeo':
                    self::setVideoVimeoInfo($videoId);
                    if (!empty(self::$videoVimeoInfo)) {
                        $output = '<iframe src="//player.vimeo.com/video/' . $videoId
                            . '?portrait=0&amp;byline=0&amp;color=E6183C" width="500" height="281" '
                            . 'frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen>'
                            . '</iframe>';
                    }
                    break;
                case 'ddb':
                    self::setVideoDdbInfo($videoId);
                    $videoPalyerId = str_replace('=', '-', $videoId);

                    /**
                     * get $videoId, $offsetStart, $offsetStop
                     * $videoId egts overwriten!
                     */
                    $extended = self::getDdbVideoTimeOffset($videoId);
                    extract($extended, EXTR_OVERWRITE);

                    if (empty($videoImage)) {
                        $videoImage = 'http://www.deutsche-digitale-bibliothek.de/binary/' . $videoId . '/mvpr/1.jpg';
                    }

                    self::$videoDdbCount = self::$videoDdbCount + 1;

                    $output = '
                    <div id="ddb-jwp-' . $videoPalyerId . '-' . self::$videoDdbCount . '">Lade den Player ...</div>
                    <script>


                        // We assume jquery is loaded as we get this in colorbox
                        if (typeof $.Gina == "undefined") {
                            $.Gina = {};
                        };

                        $.Gina.calcColorboxVideoWidth = function(maxWidth) {
                            if ($.Gina.winW < maxWidth) {
                                maxWidth = $.Gina.winW;
                            }
                            return maxWidth;
                        };

                        var os' . $videoId . ' = {
                            start: false,
                            stop: false
                        };

                        jwplayer("ddb-jwp-' . $videoPalyerId . '-' . self::$videoDdbCount . '").setup({
                            "flashplayer" : "' . web_path_to('javascripts/vendor/jwplayer/jwplayer.flash.swf') . '",
                            "html5player" : "' . web_path_to('javascripts/vendor/jwplayer/jwplayer.html5.js') . '",
                            "modes" : [{
                                type : "html5",
                                src : "' . web_path_to('javascripts/vendor/jwplayer/jwplayer.html5.js') . '"
                            }, {
                                type : "flash",
                                src : "' . web_path_to('javascripts/vendor/jwplayer/jwplayer.flash.swf') . '"
                            }, {
                                type : "download"
                            }],
                            "fallback" : true,
                            "autostart" : false,
                            "skin" : "' . web_path_to('javascripts/vendor/jwplayer/skins/five.xml') . '",
                            "controls" : true,
                            "controlbar" : "bottom",
                            "stretching" : "uniform",
                            "primary" : "html5",
                            "startparam" : "starttime",
                            image: "' . $videoImage . '",
                            file: "http://www.deutsche-digitale-bibliothek.de/binary/' . $videoId . '/full/1.mp4",
                            width: $.Gina.calcColorboxVideoWidth(500),
                            height: 281,

                        })';


                    if (!is_null($offsetStart)) {
                        $output .= '.onTime(function(e){
                            if (e.position < ' . $offsetStart . ' && os' . $videoId . '.start === false) {
                            // if (e.position < ' . $offsetStart . ') {
                                os' . $videoId . '.start  = true;
                                this.seek(' . $offsetStart . ');
                            }';
                        if (!is_null($offsetStop)) {
                            // $output .= 'if (e.position > ' . $offsetStop . ') {
                            $output .= 'if (e.position > ' . $offsetStop . ' && os' . $videoId . '.stop === false) {
                                os' . $videoId . '.stop  = true;
                                this.pause(true);
                                this.stop();
                            }
                        })';
                        } else {
                            $output .= '
                        })';
                        }
                    }

                    /**
                     * Für JWP Version > 7.0
                     */

                    // jwplayer().on("ready", function(event){
                        // jwplayer("ddb-jwp-' . $videoId . '").play();
                    // });

                    // jwplayer().on("firstFrame", function(event){
                        // jwplayer("ddb-jwp-' . $videoId . '").seek(' . $offset . ');
                    // });


                    // if (isset($offset) && !empty($offset) && $offset > 0 && 1 != 1) {
                    //     $output .= '.onReady(function(e) { this.seek(' . $offset . ');  });';
                    // } else {
                    //     $output .= ';';
                    // }

                    $output .= ';</script>';



                    break;
                default:
                    break;
            }
        }
        return $output;
    }

    /**
     * Set Vimeo video info (width, height, ...)
     *
     * @param String $videoId Video ID from shortcode
     * @see $videoVimeoInfo
     * @return void
     */
    public static function setVideoVimeoInfo($videoId)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://vimeo.com/api/v2/video/' . $videoId . '.php');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $info = curl_exec($ch);
        if (isset($info) && !empty($info) && $info != $videoId . ' not found.') {
            self::$videoVimeoInfo = @unserialize($info);
        }
        curl_close($ch);
    }

    /**
     * Getter for Vimeo video info - static $videoVimeoInfo
     *
     * @param String $videoId Video ID from shortcode
     * @return Array
     */
    public static function getVideoVimeoInfo($videoId)
    {
        if (empty(self::$videoVimeoInfo)) {
            self::setVideoVimeoInfo($videoId);
        }
        return self::$videoVimeoInfo;
    }

    /**
     * Set Set DDB video info (width, height,)
     *
     * @param String $videoId Video ID from shortcode
     * @see $videoDdbInfo
     * @return void
     */
    public static function setVideoDdbInfo($videoId)
    {
        self::$videoDdbInfo = array(
            0 => array(
                'width' => 500,
                'height' => 281,
            )
        );
    }

    /**
     * Getter for DDB video info - static $videoDdbInfo
     *
     * @param String $videoId Video ID from shortcode
     * @return Array
     */
    public static function getVideoDdbInfo($videoId)
    {
        if (empty(self::$videoDdbInfo)) {
            self::setVideoDdbInfo($videoId);
        }
        return self::$videoDdbInfo;
    }

    /**
     * Generate HTML with license information (use license shortcodes from item metadata)
     * HTML erzeugen mit Lizenzinformationen
     *
     * Hier können in der Variable $licenses die Lizenz Shortcodes angepasst werden.
     * Der Aufbau der jeweiligen Einträge ist:
     *
     * 'SHORTCODE' => array(
     *     'name' => 'Name der Lizenz',
     *     'link' => 'URL zu der Lizenzdefinition',
     *     'icon' => 'HTML Markup für die Ausgabe von Icons'
     * )
     *
     * Beim Schlüssel icon können mehrere Icons angegeben werden.
     * HTML Format für Icons:
     * '<i class="license CSS-KLASSE-DES-JEWEILGEN-ICONS"> </i>'
     *
     * Die Grafikdatei für die Icons befindet sich unter:
     * <root-der-installation>/lib/omeka/themes/ddb/images/licenses.png
     *
     * CSS-Definitionen sind in der Datei:
     * <root-der-installation>/lib/omeka/themes/ddb/css/style.css
     *
     * Definierte CSS-Class-Selectoren:
     *
     * .license-by
     * .license-nd
     * .license-nc
     * .license-sa
     * .license-pd
     * .license-pdzero
     * .license-rr
     * .license-or
     * .license-vw
     *
     *
     * @param String $licenseText License text from item metadata (Item Type Metadata or if not present from Dublin Core)
     * @return String
     */
    public static function getLicenseFromShortcode($licenseText)
    {
        $licenses = array(
            // deprecated:
            'CC-BY' => array(
                'name' => 'Namensnennung',
                'link' => 'http://creativecommons.org/licenses/by/3.0/de/',
                'icon' => '<i class="license license-by"> </i>'
            ),
            // deprecated:
            'CC-BY-ND' => array(
                'name' => 'Namensnennung - Keine Bearbeitung',
                'link' => 'http://creativecommons.org/licenses/by-nd/3.0/de/',
                'icon' => '<i class="license license-by"> </i><i class="license license-nd"> </i>'
            ),
            // deprecated:
            'CC-BY-NC' => array(
                'name' => 'Namensnennung - Nicht kommerziell',
                'link' => 'http://creativecommons.org/licenses/by-nc/3.0/de/',
                'icon' => '<i class="license license-by"> </i><i class="license license-nc"> </i>'
            ),
            // deprecated:
            'CC-BY-NC-ND' => array(
                'name' => 'Namensnennung - Nicht kommerziell - Keine Bearbeitung',
                'link' => 'http://creativecommons.org/licenses/by-nc-nd/3.0/de/',
                'icon' => '<i class="license license-by"> </i><i class="license license-nc"> </i><i class="license license-nd"> </i>'
            ),
            // deprecated:
            'CC-BY-NC-SA' => array(
                'name' => 'Namensnennung - Nicht kommerziell - Weitergabe unter gleichen Bedingungen',
                'link' => 'http://creativecommons.org/licenses/by-nc-sa/3.0/de/',
                'icon' => '<i class="license license-by"> </i><i class="license license-nc"> </i><i class="license license-sa"> </i>'
            ),
            // deprecated:
            'CC-BY-SA' => array(
                'name' => 'Namensnennung - Weitergabe unter gleichen Bedingungen',
                'link' => 'http://creativecommons.org/licenses/by-sa/3.0/de/',
                'icon' => '<i class="license license-by"> </i><i class="license license-sa"> </i>'
            ),
            'CC-PDM1' => array(
                'name' => 'Public Domain Marke 1.0  - Weltweit frei von bekannten urheberrechtlichen Einschränkungen',
                'link' => 'http://creativecommons.org/publicdomain/mark/1.0/deed.de',
                'icon' => '<i class="license license-pd"> </i>'
            ),
            'CC-PD-M1' => array(
                'name' => 'Public Domain Marke 1.0  - Weltweit frei von bekannten urheberrechtlichen Einschränkungen',
                'link' => 'http://creativecommons.org/publicdomain/mark/1.0/deed.de',
                'icon' => '<i class="license license-pd"> </i>'
            ),
            'CC-PDU1' => array(
                'name' => 'CC0 1.0 Universell - Public Domain Dedication',
                'link' => 'http://creativecommons.org/publicdomain/zero/1.0/deed.de',
                'icon' => '<i class="license license-pdzero"> </i>'
            ),
            'CC-PD-U1' => array(
                'name' => 'CC0 1.0 Universell - Public Domain Dedication',
                'link' => 'http://creativecommons.org/publicdomain/zero/1.0/deed.de',
                'icon' => '<i class="license license-pdzero"> </i>'
            ),
            'G-RR-AF' => array(
                'name' => 'Rechte vorbehalten - Freier Zugang',
                'link' => 'http://www.deutsche-digitale-bibliothek.de/lizenzen/rv-fz/',
                'icon' => '<i class="license license-rr"> </i>'
            ),
            // deprecated:
            'G-RR-AP' => array(
                'name' => 'Rechte vorbehalten - Zugang nach Zahlung einer Gebühr',
                'link' => 'https://www.deutsche-digitale-bibliothek.de/content/lizenzen/rv-bz/',
                'icon' => '<i class="license license-rr"> </i>'
            ),
            'G-RR-AA' => array(
                'name' => 'Rechte vorbehalten - Zugang nach Autorisierung',
                'link' => 'http://www.deutsche-digitale-bibliothek.de/lizenzen/rv-ez/',
                'icon' => '<i class="license license-rr"> </i>'
            ),
            // deprecated:
            'G-NA' => array(
                'name' => 'Rechtsstatus unbekannt',
                'link' => 'https://www.deutsche-digitale-bibliothek.de/content/lizenzen/unbekannt/',
                'icon' => ''
            ),
            // deprecated:
            'E-OR-NC' => array(
                'name' => 'Ungeschützt - Nicht kommerziell',
                'link' => 'http://www.europeana.eu/portal/rights/out-of-copyright-non-commercial.html/',
                'icon' => '<i class="license license-or"> </i><i class="license license-nc"> </i>'
            ),
            'CC-BY-3.0-DEU' => array(
                'name' => 'Namensnennung 3.0 Deutschland',
                'link' => 'http://creativecommons.org/licenses/by/3.0/de/',
                'icon' => '<i class="license license-by"> </i>'
            ),
            'CC-BY-4.0-INT' => array(
                'name' => 'Namensnennung 4.0 International',
                'link' => 'http://creativecommons.org/licenses/by/4.0/deed.de',
                'icon' => '<i class="license license-by"> </i>'
            ),
            'CC-BY-SA-3.0-DEU' => array(
                'name' => 'Namensnennung - Weitergabe unter gleichen Bedingungen 3.0 Deutschland',
                'link' => 'http://creativecommons.org/licenses/by-sa/3.0/de/',
                'icon' => '<i class="license license-by"> </i><i class="license license-sa"> </i>'
            ),
            'CC-BY-SA-4.0-INT' => array(
                'name' => 'Namensnennung - Weitergabe unter gleichen Bedingungen 4.0 International',
                'link' => 'http://creativecommons.org/licenses/by-sa/4.0/deed.de',
                'icon' => '<i class="license license-by"> </i><i class="license license-sa"> </i>'
            ),
            'CC-BY-ND-3.0-DEU' => array(
                'name' => 'Namensnennung - Keine Bearbeitung 3.0 Deutschland',
                'link' => 'http://creativecommons.org/licenses/by-nd/3.0/de/',
                'icon' => '<i class="license license-by"> </i><i class="license license-nd"> </i>'
            ),
            'CC-BY-ND-4.0-INT' => array(
                'name' => 'Namensnennung - Keine Bearbeitung 4.0 International',
                'link' => 'http://creativecommons.org/licenses/by-nd/4.0/deed.de',
                'icon' => '<i class="license license-by"> </i><i class="license license-nd"> </i>'
            ),
            'CC-BY-NC-3.0-DEU' => array(
                'name' => 'Namensnennung - Nicht kommerziell 3.0 Deutschland',
                'link' => 'http://creativecommons.org/licenses/by-nc/3.0/de/',
                'icon' => '<i class="license license-by"> </i><i class="license license-nc"> </i>'
            ),
            'CC-BY-NC-4.0-INT' => array(
                'name' => 'Namensnennung - Nicht kommerziell 4.0 International',
                'link' => 'http://creativecommons.org/licenses/by-nc/4.0/deed.de',
                'icon' => '<i class="license license-by"> </i><i class="license license-nc"> </i>'
            ),
            'CC-BY-NC-SA-3.0-DEU' => array(
                'name' => 'Namensnennung - Nicht kommerziell - Weitergabe unter gleichen Bedingungen 3.0 Deutschland',
                'link' => 'http://creativecommons.org/licenses/by-nc-sa/3.0/de/',
                'icon' => '<i class="license license-by"> </i><i class="license license-nc"> </i><i class="license license-sa"> </i>'
            ),
            'CC-BY-NC-SA-4.0-INT' => array(
                'name' => 'Namensnennung - Nicht kommerziell - Weitergabe unter gleichen Bedingungen 4.0 International',
                'link' => 'http://creativecommons.org/licenses/by-nc-sa/4.0/deed.de',
                'icon' => '<i class="license license-by"> </i><i class="license license-nc"> </i><i class="license license-sa"> </i>'
            ),
            'CC-BY-NC-ND-3.0-DEU' => array(
                'name' => 'Namensnennung - Nicht kommerziell - Keine Bearbeitung 3.0 Deutschland ',
                'link' => 'http://creativecommons.org/licenses/by-nc-nd/3.0/de/',
                'icon' => '<i class="license license-by"> </i><i class="license license-nc"> </i><i class="license license-nd"> </i>'
            ),
            'CC-BY-NC-ND-4.0-INT' => array(
                'name' => 'Namensnennung - Nicht kommerziell - Keine Bearbeitung 4.0 International',
                'link' => 'http://creativecommons.org/licenses/by-nc-nd/4.0/deed.de',
                'icon' => '<i class="license license-by"> </i><i class="license license-nc"> </i><i class="license license-nd"> </i>'
            ),
            'G-VW' => array(
                'name' => 'Verwaistes Werk',
                'link' => 'http://www.deutsche-digitale-bibliothek.de/lizenzen/vw/',
                'icon' => '<i class="license license-vw"> </i>'
            ),
            'G-NUG-KKN' => array(
                'name' => 'Nicht urheberrechtlich geschützt - Keine kommerzielle Nachnutzung',
                'link' => 'http://www.deutsche-digitale-bibliothek.de/lizenzen/nug-kkn/',
                'icon' => '<i class="license license-or"> </i><i class="license license-nc"> </i>'
            )
        );


        // ------------------ DO NOT EDIT BELOW THIS LINE ------------------ //
        // ----------- AB HIER BITTE KEINE AENDERUNGEN VORNEHMEN ----------- //

        $output = '';
        $matches = self::parseShortcode($licenseText);
        $matchesSize = count($matches);
        for ($i=0; $i < $matchesSize; $i++) {
            if (isset($matches[$i][2]) && 'license' == $matches[$i][2] && isset($licenses[$matches[$i][3]])) {
                $output .= '<a target="_blank" href="'
                    . $licenses[$matches[$i][3]]['link'] . '" title="'
                    . $licenses[$matches[$i][3]]['name'] . '">'
                    . $licenses[$matches[$i][3]]['icon']
                    . '</a>';
            }
        }
        if (empty($output) && !empty($licenseText)) {
            $output = strip_tags($licenseText);
        }
        return $output;
    }

    /**
     * Get item title from item meta data (Item Type Metadata or Dublin Core)
     *
     * @param Array Object $attachment Omeka item attachment
     * @param Object $file Omeka item file
     * @return String The item title
     */
    public static function getItemTitle($attachment, $file)
    {
        $attachmentTitle = '';
        if (null !== $attachment['item'] && empty($attachmentTitle)) {
            $attachmentTitle = strip_tags(metadata($attachment['item'],
                array('Item Type Metadata', 'Titel')));
        }
        if (null !== $attachment['item'] && empty($attachmentTitle)) {
            $attachmentTitle = strip_tags(metadata($attachment['item'],
                array('Dublin Core', 'Title')));
        }
        if (null !== $file  && empty($attachmentTitle)) {
            $attachmentTitle = strip_tags(metadata($file,
                array('Dublin Core', 'Title')));
        }
        return $attachmentTitle;
    }

    /**
     * Get item title from item meta data (Item Type Metadata only)
     *
     * @param Array Object $attachment Omeka item attachment
     * @param Object $file Omeka item file
     * @return String The item title
     */
    public static function getItemSubtitle($attachment, $file)
    {
        $attachmentSubtitle = '';
        if (null !== $attachment['item']) {
            $attachmentSubtitle = strip_tags(metadata($attachment['item'],
                array('Item Type Metadata', 'Weiterer Titel')));
        }
        return $attachmentSubtitle;
    }

    public static function setElementVersion()
    {
        if (!isset(self::$elementsVersion)) {
            $version = 1;
            $preCheck = false;
            $db = get_db();
            $elements = $db->getTable('Element')->findBySql('element_set_id = ?', array(3));
            foreach ($elements as $element) {
                if ($element->name == 'URL der Institution') {
                    $preCheck = true;
                    break;
                }
            }
            foreach ($elements as $element) {
                if ($preCheck == true && $element->name == 'Name der Institution') {
                    $version = 2;
                    break;
                }
            }
            self::$elementsVersion = $version;
        }
    }

    public static function getElementVersion()
    {
        if (!isset(self::$elementsVersion)) {
            self::setElementVersion();
        }
        return self::$elementsVersion;
    }

    /**
     * Get item institution from item meta data (Item Type Metadata only)
     *
     * @param Array Object $attachment Omeka item attachment
     * @param Object $file Omeka item file
     * @return String The item title
     */
    public static function getItemInstitution($attachment)
    {

        $elementVersion = self::getElementVersion();
        $output = '';

        if ($elementVersion == 1) {
            if (null !== $attachment['item']) {
                $output = metadata($attachment['item'],
                    array('Item Type Metadata', 'Institution'));
            }
        } else {
            if (null !== $attachment['item']) {
                $institutionNameLegacy = metadata($attachment['item'], array('Item Type Metadata', 'Name der Institution'));
                $institutionName = strip_tags($institutionNameLegacy);
                $institutionUrl = strip_tags(metadata($attachment['item'], array('Item Type Metadata', 'URL der Institution')));
                if (!empty($institutionName) && !empty($institutionUrl)) {
                    $output = '<a href="' . $institutionUrl . '" target="_blank">'
                        . $institutionName . '</a>';
                } elseif (!empty($institutionName) && isset($institutionNameLegacy) && !empty($institutionNameLegacy) &&
                    1 === preg_match('|href="([^"]*)|', $institutionNameLegacy, $matches))
                {
                    $output = '<a href="'
                    . $matches[1]
                    . '" target="_blank">'
                    . $institutionName
                    . '</a>';
                }
            }
        }


        return $output;
    }

    /**
     * Get item description from item meta data (Item Type Metadata or Dublin Core)
     *
     * @param Array Object $attachment Omeka item attachment
     * @param Object $file Omeka item file
     * @return String The item title
     */
    public static function getItemDescription($attachment, $file)
    {
        $attachmentDescription = '';
        $attachmentDescription = strip_tags($attachment['caption']);
        if (null !== $attachment['item'] && empty($attachmentDescription)) {
            $attachmentDescription = strip_tags(metadata($attachment['item'],
                array('Item Type Metadata', 'Kurzbeschreibung')));
        }
        if (null !== $attachment['item'] && empty($attachmentDescription)) {
            $attachmentDescription = strip_tags(metadata($attachment['item'],
                array('Dublin Core', 'Description')));
        }
        if (null !== $file && empty($attachmentDescription)) {
            $attachmentDescription = strip_tags(metadata($file,
                array('Dublin Core', 'Description')));
        }
        return $attachmentDescription;
    }

    /**
     * Get item rights (description) from item meta data (Item Type Metadata or Dublin Core)
     *
     * @param Array Object $attachment Omeka item attachment
     * @param Object $file Omeka item file
     * @return String The item title
     */
    public static function getItemRights($attachment, $file)
    {
        $attachmentRights = '';
        if (null !== $attachment['item']) {
            $attachmentRights = strip_tags(metadata($attachment['item'],
                array('Item Type Metadata', 'Rechtsstatus')));
        }
        if (null !== $attachment['item'] && empty($attachmentRights)) {
            $attachmentRights = strip_tags(metadata($attachment['item'],
                array('Dublin Core', 'Rights')));
        }
        if (null !== $file && empty($attachmentRights)) {
            $attachmentRights = strip_tags(metadata($file,
                array('Dublin Core', 'Rights')));
        }
        return self::getLicenseFromShortcode($attachmentRights);
    }

    /**
     * Get item link source from item meta data (Item Type Metadata or Dublin Core)
     *
     * @param Array Object $attachment Omeka item attachment
     * @param Object $file Omeka item file
     * @return String The item title
     */
    public static function getItemLinkText($attachment, $file)
    {
        $elementVersion = self::getElementVersion();

        $attachmenLinkText = '';
        if ($elementVersion == 1) {
            if (null !== $attachment['item']) {
                $attachmenLinkText = strip_tags(metadata($attachment['item'],
                    array('Item Type Metadata', 'Link zum Objekt')));
            }
            if (null !== $attachment['item'] && empty($attachmenLinkText)) {
                $attachmenLinkText = strip_tags(metadata($attachment['item'],
                    array('Item Type Metadata', 'Link zum Objekt bei der datenliefernden Einrichtung')));
            }
        } else {
            if (null !== $attachment['item']) {
                $attachmenLinkText = strip_tags(metadata($attachment['item'],
                    array('Item Type Metadata', 'Link zum Objekt in der DDB')));
            }
            if (null !== $attachment['item'] && empty($attachmenLinkText)) {
                $attachmenLinkText = strip_tags(metadata($attachment['item'],
                    array('Item Type Metadata', 'Link zum Objekt bei der datengebenden Institution')));
            }
        }

        if (null !== $attachment['item'] && empty($attachmenLinkText)) {
            $attachmenLinkText = strip_tags(metadata($attachment['item'],
                array('Dublin Core', 'Source')));
        }
        if (null !== $file && empty($attachmenLinkText)) {
            $attachmenLinkText = strip_tags(metadata($file,
                array('Dublin Core', 'Source')));
        }
        return $attachmenLinkText;
    }

    /**
     * Get item link title from item meta data (Item Type Metadata or Dublin Core)
     *
     * @param Array Object $attachment Omeka item attachment
     * @param Object $file Omeka item file
     * @return String The item title
     */
    public static function getItemLinkTitle($attachment, $file)
    {
        $elementVersion = self::getElementVersion();
        $attachmenLinkTitle = '';
        if ($elementVersion == 1) {
            if (null !== $attachment['item'] && 1 === preg_match('@title="([^"]*)@',
                metadata($attachment['item'], array('Item Type Metadata', 'Link zum Objekt')),
                    $matches)) {
                $attachmenLinkTitle = $matches[1];
            }
            if (null !== $attachment['item'] && empty($attachmenLinkTitle) &&
                1 === preg_match('@title="([^"]*)@', metadata($attachment['item'],
                    array('Item Type Metadata', 'Link zum Objekt bei der datenliefernden Einrichtung')),
                    $matches)) {
                $attachmenLinkTitle = $matches[1];
            }
        } else {
            if (null !== $attachment['item'] && 1 === preg_match('@title="([^"]*)@',
                metadata($attachment['item'], array('Item Type Metadata', 'Link zum Objekt in der DDB')),
                    $matches)) {
                $attachmenLinkTitle = $matches[1];
            }
            if (null !== $attachment['item'] && empty($attachmenLinkTitle)
                && !empty(strip_tags(metadata($attachment['item'], array('Item Type Metadata', 'Link zum Objekt in der DDB'))))
                && strip_tags(metadata($attachment['item'], array('Item Type Metadata', 'Link zum Objekt in der DDB'))) ==
                metadata($attachment['item'], array('Item Type Metadata', 'Link zum Objekt in der DDB'))
            ) {
                $attachmenLinkTitle = metadata($attachment['item'], array('Item Type Metadata', 'Link zum Objekt in der DDB'));
            }
            if (null !== $attachment['item'] && empty($attachmenLinkTitle) &&
                1 === preg_match('@title="([^"]*)@', metadata($attachment['item'],
                    array('Item Type Metadata', 'Link zum Objekt bei der datengebenden Institution')),
                    $matches)) {
                $attachmenLinkTitle = $matches[1];
            }
            if (null !== $attachment['item'] && empty($attachmenLinkTitle)
                && !empty(strip_tags(metadata($attachment['item'], array('Item Type Metadata', 'Link zum Objekt bei der datengebenden Institution'))))
                && strip_tags(metadata($attachment['item'], array('Item Type Metadata', 'Link zum Objekt bei der datengebenden Institution'))) ==
                metadata($attachment['item'], array('Item Type Metadata', 'Link zum Objekt bei der datengebenden Institution'))
            ) {
                $attachmenLinkTitle = metadata($attachment['item'], array('Item Type Metadata', 'Link zum Objekt bei der datengebenden Institution'));
            }
        }

        if (null !== $attachment['item'] && empty($attachmenLinkTitle) &&
            1 === preg_match('@title="([^"]*)@', metadata($attachment['item'],
                array('Dublin Core', 'Source')), $matches)) {
            $attachmenLinkTitle = $matches[1];
        }
        if (null !== $file && empty($attachmenLinkTitle) && 1 === preg_match('@title="([^"]*)@',
            metadata($file, array('Dublin Core', 'Source')), $matches)) {
            $attachmenLinkTitle = $matches[1];
        }
        return $attachmenLinkTitle;
    }

    /**
     * Get item link url from item meta data (Item Type Metadata or Dublin Core)
     *
     * @param Array Object $attachment Omeka item attachment
     * @param Object $file Omeka item file
     * @return String The item title
     */
    public static function getItemLinkUrl($attachment, $file)
    {
        $elementVersion = self::getElementVersion();

        $attachmentLinkUrl = '';
        if ($elementVersion == 1) {
            if (null !== $attachment['item'] && 1 === preg_match('|href="([^"]*)|',
                metadata($attachment['item'], array('Item Type Metadata', 'Link zum Objekt')),
                    $matches)) {
                $attachmentLinkUrl = $matches[1];
            }
        } else {
            if (null !== $attachment['item'] && 1 === preg_match('|href="([^"]*)|',
                metadata($attachment['item'], array('Item Type Metadata', 'Link zum Objekt in der DDB')),
                    $matches)) {
                $attachmentLinkUrl = $matches[1];
            }
            if (null !== $attachment['item'] && empty($attachmentLinkUrl)
                && !empty(strip_tags(metadata($attachment['item'], array('Item Type Metadata', 'Link zum Objekt in der DDB'))))
                && strip_tags(metadata($attachment['item'], array('Item Type Metadata', 'Link zum Objekt in der DDB'))) ==
                metadata($attachment['item'], array('Item Type Metadata', 'Link zum Objekt in der DDB'))
            ) {
                $attachmentLinkUrl = metadata($attachment['item'], array('Item Type Metadata', 'Link zum Objekt in der DDB'));
            }
        }
        // if (null !== $attachment['item'] && empty($attachmentLinkUrl) &&
        //     1 === preg_match('@href="([^"]*)@', metadata($attachment['item'],
        //         array('Item Type Metadata', 'Link zum Objekt bei der datengebenden Institution')),
        //         $matches)) {
        //     $attachmentLinkUrl = $matches[1];
        // }
        // if (null !== $file && empty($attachmentLinkUrl) && 1 === preg_match('@href="([^"]*)@',
        //     metadata($file, array('Dublin Core', 'Source')), $matches)) {
        //     $attachmentLinkUrl = $matches[1];
        // } else {
        //     $attachmentLinkUrl = record_url($attachment['item'], 'show', false);
        // }
        if (empty($attachmentLinkUrl)) {
            $attachmentLinkUrl = record_url($attachment['item'], 'show', false);
        }
        return $attachmentLinkUrl;
    }

    /**
     * Get thumbnails and links to Colorbox for gallery items
     *
     * Thubnail galleries are used in the templates:
     * - ddb-full-right
     * - ddb-full-right-carousel
     * - ddb-gallery-thumbnais
     *
     * @param int $start Start with image No. from the list of attachments
     * @param int $end Stop at image No. from the list of attachments
     * @param array $props Array of image attributes like (class, alt, title etc.)
     * @param string $thumbnailType Size or type of the thumbnail like (thumbnail, square_thumbnail, fullsize)
     * @param array $linkOptions Array of link attributes like (class, alt etc.)
     *              also colorbox data attributes like data-copyright etc.
     * @return string HTML for linked thumbnail gallery item
     */
    public static function getThumbnailGallery($start, $end, $props = array(),
        $thumbnailType = 'square_thumbnail', $linkOptions = array())
    {

        $html = '';
        $colCount = 0;
        for ($i = (int)$start; $i <= (int)$end; $i++) {
            if ($attachment = exhibit_builder_page_attachment($i)) {

                $colCount++;

                if (($colCount % 3) === 0) {
                    $addExhibitItemClass = ' last-item-in-line';
                } else {
                    $addExhibitItemClass = '';
                }

                $videoSrc = metadata($attachment['item'], array('Item Type Metadata', 'Videoquelle'));
                $html .= "\n" . '<div class="exhibit-item' . $addExhibitItemClass . '">';

                // image or video with custom placeholder image
                if ($attachment['file']) {
                    $file = $attachment['file'];
                    $attachmentTitle = self::getItemTitle($attachment, $file);
                    $attachmentSubtitle = self::getItemSubtitle($attachment, $file);
                    $attachmentInstitution = self::getItemInstitution($attachment);
                    $attachmentInstitution = (empty($attachmentInstitution))? '' : $attachmentInstitution ;
                    if (!preg_match('|\<\/p\>[\s]*$|', $attachmentInstitution) && !empty($attachmentInstitution)) {
                        $attachmentInstitution = $attachmentInstitution  . '<br>';
                    }
                    $attachmentDescription = self::getItemDescription($attachment, $file);
                    $attachmentRights = self::getItemRights($attachment, $file);
                    $attachmenLinkText = self::getItemLinkText($attachment, $file);
                    $attachmenLinkTitle = self::getItemLinkTitle($attachment, $file);
                    if (empty($attachmenLinkTitle)) {
                        $attachmenLinkTitle = $attachmentTitle;
                    }
                    $attachmentLinkUrl = self::getItemLinkUrl($attachment, $file);
                    $currentLinkOptions = array();
                    $currentLinkOptions = array_merge($linkOptions, array(
                        'data-title' => $attachmentTitle,
                        'data-subtitle' => $attachmentSubtitle,
                        'data-description' => $attachmentDescription,
                        'data-linktext' => $attachmenLinkText,
                        'data-linkurl' => $attachmentLinkUrl,
                        'data-linktitle' => $attachmenLinkTitle,
                        'data-copyright' => $attachmentInstitution . $attachmentRights,
                        'title' => $attachmentTitle,
                        'alt' => $attachmentTitle
                        ));

                    if($videoSrc) {
                        $videoType = self::getVideotypeFromShortcode($videoSrc);
                        if ($videoType === 'vimeo') {
                            // unset($currentLinkOptions['data-title']);
                            // unset($currentLinkOptions['data-subtitle']);
                            // unset($currentLinkOptions['data-description']);
                        }
                    }
                    if (!empty($attachmentTitle)) {
                        $props['title'] = $attachmentTitle;
                    }
                    $thumbnail = file_image($thumbnailType, $props, $attachment['file']);

                    if($videoSrc) {
                        $thumbnail .= '<div class="blurb">Video</div>';
                    }

                    $html .= exhibit_builder_link_to_exhibit_item($thumbnail, $currentLinkOptions, $attachment['item']);

                }

                // Video
                elseif($videoSrc) {

                    $thumbnail = self::getVideoThumbnailFromShortcode($videoSrc);
                    if (!empty($thumbnail)) {
                        $attachmentTitle = self::getItemTitle($attachment, null);
                        $attachmentSubtitle = self::getItemSubtitle($attachment, null);
                        $attachmentInstitution = self::getItemInstitution($attachment);
                        $attachmentInstitution = (empty($attachmentInstitution))? '' : $attachmentInstitution;
                        if (!preg_match('|\<\/p\>[\s]*$|', $attachmentInstitution) && !empty($attachmentInstitution)) {
                            $attachmentInstitution = $attachmentInstitution  . '<br>';
                        }
                        $attachmentDescription = self::getItemDescription($attachment, null);
                        $attachmentRights = self::getItemRights($attachment, null);
                        $attachmenLinkText = self::getItemLinkText($attachment, null);
                        $attachmenLinkTitle = self::getItemLinkTitle($attachment, null);
                        if (empty($attachmenLinkTitle)) {
                            $attachmenLinkTitle = $attachmentTitle;
                        }
                        $attachmentLinkUrl = self::getItemLinkUrl($attachment, null);
                        $currentLinkOptions = array();
                        $currentLinkOptions = array_merge($linkOptions, array(
                            'data-linktext' => $attachmenLinkText,
                            'data-linkurl' => $attachmentLinkUrl,
                            'data-linktitle' => $attachmenLinkTitle,
                            'data-copyright' => $attachmentInstitution . $attachmentRights,
                            'title' => $attachmentTitle,
                            'alt' => $attachmentTitle,
                        ));

                        $videoType = self::getVideotypeFromShortcode($videoSrc);
                        if ($videoType == 'ddb' || $videoType == 'vimeo') {
                            $currentLinkOptions = array_merge($currentLinkOptions, array(
                                'data-title' => $attachmentTitle,
                                'data-subtitle' => $attachmentSubtitle,
                                'data-description' => $attachmentDescription,
                            ));
                        }

                        $html .= exhibit_builder_link_to_exhibit_item($thumbnail, $currentLinkOptions, $attachment['item']);
                    }
                }

                // X3D
                elseif (!isset($attachment['file']) && isset($attachment['item'])) {
                    $item = $attachment['item'];
                    $x3d = get_db()->getTable('X3d')->findByItemId($item->id);
                    if (isset($x3d) && !empty($x3d)) {
                        $x3dDir = FILES_DIR . DIRECTORY_SEPARATOR . 'x3d' . DIRECTORY_SEPARATOR . $x3d->directory;
                        $x3dWebdir = WEB_FILES . '/x3d/' . $x3d->directory;
                        $thumbnail = '<img src="' . WEB_FILES . '/x3d/' . $x3d->directory . '/sq_' . $x3d->thumbnail . '" alt="' . $attachmentTitle . '">';

                        $attachmentTitle = self::getItemTitle($attachment, null);
                        $attachmentSubtitle = self::getItemSubtitle($attachment, null);
                        $attachmentInstitution = self::getItemInstitution($attachment);
                        $attachmentInstitution = (empty($attachmentInstitution))? '' : $attachmentInstitution;
                        if (!preg_match('|\<\/p\>[\s]*$|', $attachmentInstitution) && !empty($attachmentInstitution)) {
                            $attachmentInstitution = $attachmentInstitution  . '<br>';
                        }
                        $attachmentDescription = self::getItemDescription($attachment, null);
                        $attachmentRights = self::getItemRights($attachment, null);
                        $attachmenLinkText = self::getItemLinkText($attachment, null);
                        $attachmenLinkTitle = self::getItemLinkTitle($attachment, null);
                        if (empty($attachmenLinkTitle)) {
                            $attachmenLinkTitle = $attachmentTitle;
                        }
                        $attachmentLinkUrl = self::getItemLinkUrl($attachment, null);
                        $currentLinkOptions = array();
                        $currentLinkOptions = array_merge($linkOptions, array(
                            'data-title' => $attachmentTitle,
                            'data-subtitle' => $attachmentSubtitle,
                            'data-description' => $attachmentDescription,
                            'data-linktext' => $attachmenLinkText,
                            'data-linkurl' => $attachmentLinkUrl,
                            'data-linktitle' => $attachmenLinkTitle,
                            'data-copyright' => $attachmentInstitution . $attachmentRights,
                            'title' => $attachmentTitle,
                            'alt' => $attachmentTitle,
                        ));

                        $attachmentLinkUrl = exhibit_builder_exhibit_item_uri($attachment['item']);
                        $link = '<a href="' . $attachmentLinkUrl . '" class="permalink iframe" '
                            . tag_attributes($currentLinkOptions)
                            . '>' . $thumbnail . '</a>';
                        $html .= $link;
                    }
                }

                $html .= '</div>' . "\n";
            }
        }
        return apply_filters('exhibit_builder_thumbnail_gallery', $html,
            array('start' => $start, 'end' => $end, 'props' => $props, 'thumbnail_type' => $thumbnailType));
    }

    /**
     * Get HTML (link to Colorbox and image) for attachments
     *
     * Used for main image displays in all templates
     *
     * @param array $attachment array with keys file, item, caption etc.
     * @return string HTML for linked item
     */
    public static function getAttachmentMarkup($attachment)
    {
        $file = $attachment['file'];
        $attachmentTitle = self::getItemTitle($attachment, $file);
        $attachmentSubtitle = self::getItemSubtitle($attachment, $file);
        $attachmentInstitution = self::getItemInstitution($attachment);
        $attachmentInstitution = (empty($attachmentInstitution))? '' : $attachmentInstitution;
        if (!preg_match('|\<\/p\>[\s]*$|', $attachmentInstitution) && !empty($attachmentInstitution)) {
            $attachmentInstitution = $attachmentInstitution  . '<br>';
        }
        $attachmentDescription = self::getItemDescription($attachment, $file);
        $attachmentRights = self::getItemRights($attachment, $file);
        $attachmenLinkText = self::getItemLinkText($attachment, $file);
        $attachmenLinkTitle = self::getItemLinkTitle($attachment, $file);
        if (empty($attachmenLinkTitle)) {
            $attachmenLinkTitle = $attachmentTitle;
        }
        $attachmentLinkUrl = self::getItemLinkUrl($attachment, $file);
        $linkAttributes = array(
            // 'rel'=>'ddb-omeka-gallery-1',
            'data-title' => $attachmentTitle,
            'data-subtitle' => $attachmentSubtitle,
            'data-description' => $attachmentDescription,
            'data-linktext' => $attachmenLinkText,
            'data-linkurl' => $attachmentLinkUrl,
            'data-linktitle' => $attachmenLinkTitle,
            'data-copyright' => $attachmentInstitution . $attachmentRights,
        );
        $thumbnail = null;
        $addCssClass = '';
        $videoSrc = metadata($attachment['item'], array('Item Type Metadata', 'Videoquelle'));

        // X3D
        if (!isset($file) && isset($attachment['item'])) {
            $item = $attachment['item'];
            $x3d = get_db()->getTable('X3d')->findByItemId($item->id);
            if (isset($x3d) && !empty($x3d)) {
                $x3dDir = FILES_DIR . DIRECTORY_SEPARATOR . 'x3d' . DIRECTORY_SEPARATOR . $x3d->directory;
                $x3dWebdir = WEB_FILES . '/x3d/' . $x3d->directory;
                $thumbnail = '<img src="' . WEB_FILES . '/x3d/' . $x3d->directory . '/or_' . $x3d->thumbnail . '" alt="' . $attachmentTitle . '">';
                // $attachmentLinkUrl = record_url($attachment['item'], 'show', false);
                $attachmentLinkUrl = exhibit_builder_exhibit_item_uri($attachment['item']);
                $link = '<a href="' . $attachmentLinkUrl . '" class="permalink iframe" '
                    . tag_attributes($linkAttributes)
                    . '>' . $thumbnail . '</a>';
                $link .= exhibit_builder_attachment_caption($attachment);
                return $link;
            }
        }

        // Video
        if($videoSrc) {

            if (isset($file)) {
                $imgAttributes = array('alt' => $attachmentTitle, 'class' => 'full');
                $uri = html_escape($file->getWebPath('fullsize'));
                $thumbnail = '<div class="exhibit-item-inner-container"><img src="' . $uri . '" ' . tag_attributes($imgAttributes) . '/>' . "\n"
                    . '<div class="blurb-main-image">Video</div></div>';
            } else {
                $thumbnail = '<div class="exhibit-item-inner-container">' . self::getVideoThumbnailFromShortcodeForMainItem($videoSrc, 'large')
                    . '<div class="blurb-main-image">Video</div></div>';
            }

            if (!empty($thumbnail)) {

                $videoType = self::getVideotypeFromShortcode($videoSrc);
                if ($videoType === 'vimeo') {
                    // unset($linkAttributes['data-title']);
                    // unset($linkAttributes['data-subtitle']);
                    // unset($linkAttributes['data-description']);
                }

                $currentLinkOptions = array();
                $currentLinkOptions = array_merge($linkAttributes, array(
                    'title' => $attachmentTitle,
                    'alt' => $attachmentTitle,
                ));
                $caption = exhibit_builder_attachment_caption($attachment);
                return exhibit_builder_link_to_exhibit_item($thumbnail, $currentLinkOptions, $attachment['item']) . $caption;
            }
        }

        return exhibit_builder_attachment_markup(
            $attachment,
            array(
                'imageSize' => 'fullsize',
                'linkAttributes' => $linkAttributes),
            array('class' => 'permalink' . $addCssClass),
            $thumbnail);

    }
}
