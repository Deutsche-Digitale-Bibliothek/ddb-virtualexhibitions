<?php
/**
 * Omeka Exhibit Extension and Helper for DDB - Deutsche Digitale Bibliothek
 *
 * @copyright Copyright 2018 Viktor Grandgeorg, Grandgeorg Websolutions
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

    /**
     * Omeka DDB elements version
     *
     * @var int
     */
    public static $elementsVersion = null;

    /**
     * Container for all requested DDB video object source and metadata
     *
     * @var array
     */
    public static $ddbVideoXml = array();

    /**
     * DDB XML Root Element Name
     *
     * Shoud be something like e.g.
     * ns2, ns4, tag0, cortex
     * For now we do not need to know this.
     *
     * @var string
     */
    // public static $ddbXmlRootName = '';

    /**
     * DDB Helper configuration
     *
     * Change ddbXmlSrv to get XML data from another server
     * Change ddbIIFResHelperSrvPrefix to get image resolution data from another server
     * Change ddbIIIFSrvPrefix to get video image files from another server
     * Change ddbVideoSrvPrefix to get video files from another server
     *
     * @var array
     */
    public static $config = array(
        'ddbXmlSrv'                 => 'https://www.deutsche-digitale-bibliothek.de/item/xml/',
        'ddbIIFResHelperSrvPrefix'  => 'https://iiif.deutsche-digitale-bibliothek.de/image/2/',
        'ddbIIFResHelperSrvPostfix' => '/info.json',
        'ddbIIIFSrvPrefix'          => 'https://iiif.deutsche-digitale-bibliothek.de/image/2/',
        'ddbIIIFSrvMiddfix'         => '/full/!',
        'ddbIIIFSrvPostfix'         => '/0/default.jpg',
        'ddbVideoSrvPrefix'         => 'https://iiif.deutsche-digitale-bibliothek.de/binary/'
    );

    public static $currentAttechmentMediaType = 'text';

    public static $vimeoVideoCounter = 0;

    public static $colorPalettes = null;

    public static function getVimeoVideoCounter()
    {
        self::$vimeoVideoCounter = self::$vimeoVideoCounter + 1;
        return self::$vimeoVideoCounter;
    }


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
     * Main method to get ddb video data
     *
     * @param string $id DDB object ID (from the shortcode)
     * @return void
     */
    public static function getDdbVideoXml($id)
    {
        if (isset(self::$ddbVideoXml[$id])) {
            return;
        }
        $ch = curl_init();
        curl_setopt(
            $ch,
            CURLOPT_URL,
            self::$config['ddbXmlSrv'] . $id
        );
        // curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($httpCode === 200) {
            $doc = new DOMDocument();
            $doc->loadXML($response);
            // self::$ddbXmlRootName = self::getDdbVideoXmlRootName($doc, $id);
            self::getDdbVideoXmlBin($doc, $id);
        }
    }

    /**
     * Get root tag name
     *
     * This is unused for now
     *
     * @param DOMDocument object $doc
     * @return stirng root element tag name
     */
    public static function getDdbVideoXmlRootName($doc)
    {
        $root = $doc->documentElement;
        return (substr($root->tagName, 0, strpos($root->tagName, ':')));
    }

    /**
     * Get image resolution for video images.
     *
     * @param int $id DDB object ID
     * @param string $ref Hash from XML for the image file
     * @return void
     */
    public static function getDdbVideoImgResolution($id, $ref)
    {

        if (isset(self::$ddbVideoXml[$id]['img']['res'])) {
            return false;
        }
        $ch = curl_init();
        curl_setopt(
            $ch,
            CURLOPT_URL,
            self::$config['ddbIIFResHelperSrvPrefix'] . $ref . self::$config['ddbIIFResHelperSrvPostfix']
        );
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        // return '800,600';
        if ($response) {
            $sizes = json_decode($response);
            if (isset($sizes->sizes) && !empty($sizes->sizes) && is_array($sizes->sizes) && isset($sizes->width) && isset($sizes->height)) {
                foreach ($sizes->sizes as $size) {
                    if ($size->width <= $sizes->width && $size->height <= $sizes->height) {
                        return $size->width . ',' . $size->height;
                    }
                }
            }
            // return json_decode($response);
        }
        return false;
    }

    /**
     * Iterate over binary nodes and get attribs for video and video img.
     * Set self::$ddbVideoXml foreach video id.
     *
     * @param DOMNode $domNode The XML DOMNode for the DDB object
     * @param sting $id DDB object ID
     * @return void
     */
    public static function getDdbVideoXmlBin(DOMNode $domNode, $id)
    {
        $mimes = array(
            'img' => array(
                'image/jpeg'
            ),
            'video' => array(
                'video/mp4'
            )
        );
        $root = $domNode->documentElement;

        foreach ($domNode->getElementsByTagNameNS($root->namespaceURI, 'binary') as $node) {
            $ref = $node->getAttribute('ref');
            $mimetype = $node->getAttribute('mimetype');
            if (in_array($mimetype, $mimes['video'])) {
                self::$ddbVideoXml[$id]['video'] = [
                    'ref' => $ref,
                    'mimetype' => $mimetype,
                    'mime' => substr($mimetype, (strpos($mimetype, '/') + 1)),
                    'src' => self::$config['ddbVideoSrvPrefix'] . $ref
                ];
            }
            if (in_array($mimetype, $mimes['img']) && $node->getAttribute('primary') === 'true') {
                $res = self::getDdbVideoImgResolution($id, $ref);
                self::$ddbVideoXml[$id]['img'] = [
                    'ref' => $ref,
                    'mimetype' => $mimetype,
                    'res' => $res,
                    'src' => self::$config['ddbIIIFSrvPrefix']
                        . $ref
                        . self::$config['ddbIIIFSrvMiddfix']
                        . $res
                        . self::$config['ddbIIIFSrvPostfix']
                ];
            }
        }
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
                    $extended = self::getDdbVideoTimeOffset($videoId);
                    extract($extended, EXTR_OVERWRITE);
                    if (!array_key_exists($videoId, self::$ddbVideoXml)) {
                        self::getDdbVideoXml($videoId);
                    }
                    if (array_key_exists($videoId, self::$ddbVideoXml)) {
                        $output = '<div class="external-thumbnail" '
                            . 'style="background-image:url(\''
                            . self::$ddbVideoXml[$videoId]['img']['src']
                            . '\');"><img src="'
                            . img('thnplaceholder.gif')
                            . '" alt="video" style="'
                            . 'visibility:hidden;'
                            . '">'
                            . '<div class="blurb">Video</div></div>';
                    }
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
     * @param string $outputType Output type HTML or URL
     * @return string
     */
    public static function getVideoThumbnailFromShortcodeForMainItem($metaDataVideoSource, $thumbnailsize = 'large', $outputType = null)
    {
        $output = '';
        $matches = self::parseShortcode($metaDataVideoSource);
        $videoType = self::getVideotypeFromShortcode($matches);
        if ($videoType !== 'none') {
            list(, $videoId) = explode(":", $matches[0][3]);
            switch ($videoType) {
                case 'vimeo':
                    // we do not need thumbnails for vimeo here ...
                    // self::setVideoVimeoInfo($videoId);
                    // $videoInfo = self::getVideoVimeoInfo($videoId);
                    // var_dump($videoInfo);
                    // if (isset($videoInfo['thumbnail_url']) && !empty($videoInfo['thumbnail_url'])) {
                    //     if (isset($outputType) && $outputType === 'URL') {
                    //         $output = $videoInfo['thumbnail_url'];
                    //     } else {
                    //         $output = '<img src="'
                    //             . $videoInfo['thumbnail_url'] . '" alt="video" >';

                    //     }
                    // }
                    break;
                case 'ddb':
                    self::setVideoDdbInfo($videoId);
                    // get $videoId, $offsetStart, $offsetStop:
                    $extended = self::getDdbVideoTimeOffset($videoId);
                    extract($extended, EXTR_OVERWRITE);
                    if (!array_key_exists($videoId, self::$ddbVideoXml)) {
                        self::getDdbVideoXml($videoId);
                    }
                    if (array_key_exists($videoId, self::$ddbVideoXml)) {
                        if (isset($outputType) && $outputType === 'URL') {
                            if (isset(self::$ddbVideoXml[$videoId]['img'])) {
                                $output = self::$ddbVideoXml[$videoId]['img']['src'];
                            } else {
                                $output = img('video_placeholder.png');
                            }
                        } else {
                            if (isset(self::$ddbVideoXml[$videoId]['img'])) {
                                $output = '<img src="'
                                    . self::$ddbVideoXml[$videoId]['img']['src']
                                    . '" alt="video">';
                            } else {
                                $output = '<img src="'
                                    . img('video_placeholder.png')
                                    . '" alt="video">';
                            }
                        }
                    }
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
                    if (!empty(self::$videoVimeoInfo) && isset(self::$videoVimeoInfo{'html'})) {
                        $counter = self::getVimeoVideoCounter();
                        // $output = self::$videoVimeoInfo{'html'};
                        // var_dump(self::$videoVimeoInfo);
                        $output = '<div class="litfass-vimeo-video" ' .
                        'id="vimeo-video-' . self::$videoVimeoInfo['video_id'] . '-c-' . $counter .  '" ' .
                        'data-ddb-vimeo-id="' . self::$videoVimeoInfo['video_id'] . '" ' .
                        'data-ddb-vimeo-width="' . self::$videoVimeoInfo['width'] . '" ' .
                        '></div><div class="recapute-tab" tabindex="0"></div>';
                    }
                    break;
                case 'ddb':
                    self::setVideoDdbInfo($videoId);
                    $videoPalyerId = str_replace('=', '-', $videoId);
                    /**
                     * get $videoId, $offsetStart, $offsetStop
                     * $videoId gets overwriten!
                     */
                    $extended = self::getDdbVideoTimeOffset($videoId);
                    extract($extended, EXTR_OVERWRITE);
                    if (!array_key_exists($videoId, self::$ddbVideoXml)) {
                        self::getDdbVideoXml($videoId);
                    }

                    // Get Video Thumbnails
                    if (empty($videoImage) &&
                        array_key_exists($videoId, self::$ddbVideoXml) &&
                        isset(self::$ddbVideoXml[$videoId]['img'])
                    ) {
                        $videoImage = self::$ddbVideoXml[$videoId]['img']['src'];
                    } elseif (empty($videoImage)) {
                        $videoImage = img('video_placeholder.png');
                    }

                    if (array_key_exists($videoId, self::$ddbVideoXml) && isset(self::$ddbVideoXml[$videoId]['video'])) {
                        self::$videoDdbCount = self::$videoDdbCount + 1;
                        $output = '
                        <div class="litfass_video_container">
                        <div id="ddb-jwp-' . $videoPalyerId . '-' . self::$videoDdbCount . '">Lade den Player ...</div>
                        <script>
                            if (typeof window.Gina == "undefined") {
                                window.Gina = {};
                            };

                            window.Gina.calcVideoWidth = function(maxWidth) {
                                if (typeof window.Gina.hasOwnProperty("winW") && window.Gina.winW < maxWidth) {
                                    maxWidth = window.Gina.winW;
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
                                "image": "' . $videoImage . '",
                                "type": "' . self::$ddbVideoXml[$videoId]['video']['mime'] . '",
                                "file": "' .  self::$ddbVideoXml[$videoId]['video']['src'] . '",
                                "width": window.Gina.calcVideoWidth(469),
                                "height": 281,

                            })';

                        if (!is_null($offsetStart)) {
                            $output .= '.onTime(function(e){
                                if (e.position < ' . $offsetStart . ' && os' . $videoId . '.start === false) {
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

                        $output .= ';</script>';
                        $output .= '</div>';

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


                    break;
                default:
                    break;
            }
        }
        return $output;
    }

    /**
     * Get params for attachments used as background image or video
     *
     * @param array $attachment array with keys file, item, caption etc.
     * @return array params for background attachamnts
     */
    public static function getBackgroundAttachment($attachment)
    {
        $backgroundSrc = array(
            'type'      => '',
            'imgSrc'    => '',
            'videoSrc'  => '',
            'videoMimeType' => '',
            'offsetStart' => null,
            'offsetStop' => null
        );
        if (!$attachment) {
            $backgroundSrc['type'] = 'none';
            return $backgroundSrc;
        }

        $item = $attachment['item'];
        $file = $attachment['file'];

        if (isset($file) && !empty($file) && is_object($file)) {
            $backgroundSrc['type'] = 'img';
            $backgroundSrc['imgSrc'] = self::getFullsizeImageUrl($attachment);
        }

        if ($item) {
            $videoSrc = metadata($attachment['item'], array('Item Type Metadata', 'Videoquelle'));
            if($videoSrc !== null && !empty($videoSrc)) {
                $matches = self::parseShortcode($videoSrc);
                if (isset($matches[0][2]) && 'video' == $matches[0][2] && isset($matches[0][3])) {
                    list($videoType, $videoId) = explode(":", $matches[0][3]);
                    switch ($videoType) {
                        case 'vimeo':
                            $backgroundSrc['type'] = 'vimeo';
                            self::setVideoVimeoInfo($videoId);
                            if (!empty(self::$videoVimeoInfo)) {
                                $backgroundSrc['info'] = self::$videoVimeoInfo;
                            }
                            // $backgroundSrc['videoSrc'] = '//player.vimeo.com/video/' . $videoId . '/';
                            break;
                        case 'ddb':
                            $extended = self::getDdbVideoTimeOffset($videoId);
                            // extract($extended, EXTR_OVERWRITE);
                            $videoId = $extended['videoId'];
                            $backgroundSrc['offsetStart'] = $extended['offsetStart'];
                            $backgroundSrc['offsetStop'] = $extended['offsetStop'];

                            self::setVideoDdbInfo($videoId);
                            if (!array_key_exists($videoId, self::$ddbVideoXml)) {
                                self::getDdbVideoXml($videoId);
                            }
                            if (array_key_exists($videoId, self::$ddbVideoXml) &&
                                isset(self::$ddbVideoXml[$videoId]['video'])) {
                                $backgroundSrc['type'] = 'ddb-video';
                                $backgroundSrc['videoSrc'] = self::$ddbVideoXml[$videoId]['video']['src'];
                                $backgroundSrc['videoMimeType'] = self::$ddbVideoXml[$videoId]['video']['mime'];
                            }
                            // Specifying_playback_range:
                            if (isset($backgroundSrc['offsetStart'])) {
                                $backgroundSrc['videoSrc'] .= '#t=' . $backgroundSrc['offsetStart'];
                            }
                            if (isset($backgroundSrc['offsetStop'])) {
                                if (isset($backgroundSrc['offsetStart'])) {
                                    $backgroundSrc['videoSrc'] .= ',' . $backgroundSrc['offsetStop'];
                                } else {
                                    $backgroundSrc['videoSrc'] .= '#t=,' . $backgroundSrc['offsetStop'];
                                }
                            }
                            if (array_key_exists($videoId, self::$ddbVideoXml) &&
                                isset(self::$ddbVideoXml[$videoId]['img']) &&
                                empty($backgroundSrc['imgSrc'])
                            ) {
                                $backgroundSrc['imgSrc'] = self::$ddbVideoXml[$videoId]['img']['src'];
                            }
                            break;
                        default:
                            break;
                    }
                }
            }
        }
        return $backgroundSrc;
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
        // curl_setopt($ch, CURLOPT_PROXY, 'ddbproxy.deutsche-digitale-bibliothek.de:8888');
        if (!isset($_SERVER['PROXY_ENV'])) {
            curl_setopt($ch, CURLOPT_PROXY, 'proxy.fiz-karlsruhe.de:8888');
        } elseif ($_SERVER['PROXY_ENV'] !== 'none') {
            curl_setopt($ch, CURLOPT_PROXY, $_SERVER['PROXY_ENV']);
        }
        curl_setopt($ch, CURLOPT_URL, 'https://vimeo.com/api/oembed.json?url=https://vimeo.com/' . $videoId . '');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $info = curl_exec($ch);
        if (isset($info) && !empty($info)) {
            self::$videoVimeoInfo = json_decode($info, true);
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
                'width' => 469,
                'height' => 264,
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
                $output .= '<a target="_blank" rel="noopener" href="'
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

    /**
     * Set the DDB elementset version $elementsVersion (to 1 or 2)
     *
     * Dectect version depending on present element names
     *
     * @return void
     */
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

    /**
     * Get $elementsVersion
     *
     * @return int self::$elementsVersion
     */
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
                } elseif (!empty($institutionName)) {
                    $output = $institutionName;
                }
            }
        }


        return $output;
    }

    public static function getItemInfo($attachment, $sectionCounter)
    {
        $markup = '';
        if (!$attachment) { return $markup; }
        $item = $attachment['item'];
        if (!$item) { return $markup; }
        $metadata = item_type_elements($item);

        // Titel
        if (isset($metadata['Titel']) && !empty($metadata['Titel'])) {
            $markup .= '<h3>' . $metadata['Titel'] . '</h3>';
        }
        // Untertitel
        $subtitle = '';
        if (isset($metadata['Beteiligte Personen und Organisationen']) && !empty($metadata['Beteiligte Personen und Organisationen'])) {
            $subtitle .= $metadata['Beteiligte Personen und Organisationen'];
        }
        if (isset($metadata['Typ']) && !empty($metadata['Typ'])) {
            if (!empty($subtitle)) {
                $subtitle .= ', ';
            }
            $subtitle .= $metadata['Typ'];
        }
        if (isset($metadata['Zeit']) && !empty($metadata['Zeit'])) {
            if (!empty($subtitle)) {
                $subtitle .= ', ';
            }
            $subtitle .= $metadata['Zeit'];
        }
        if (isset($metadata['Ort']) && !empty($metadata['Ort'])) {
            if (!empty($subtitle)) {
                $subtitle .= ', ';
            }
            $subtitle .= $metadata['Ort'];
        }
        if (!empty($subtitle)) {
            $markup .= '<h4>' . $subtitle . '</h4>';
        }

        // Sammlung
        if (isset($metadata['Name der Institution']) && !empty($metadata['Name der Institution'])) {
            $markup .= '<h5>Aus der Sammlung von</h5><p>';
            if (isset($metadata['URL der Institution']) && !empty($metadata['URL der Institution'])) {
                $markup .= '<a target="_blank" rel="noopener" href="' . strip_tags($metadata['URL der Institution']) . '">';
            }
            $markup .= $metadata['Name der Institution'];
            if (isset($metadata['URL der Institution']) && !empty($metadata['URL der Institution'])) {
                $markup .= '</a>';
            }
            $markup .= '</p>';
        }

        // copyright
        $markup .= '<h5>Wie darf ich das Objekt nutzen?</h5><p class="licenses">';
        $markup .= self::getItemRights($attachment, null);
        $markup .= '</p>';

        // Quelle
        if (isset($metadata['Copyright']) && !empty($metadata['Copyright'])) {
            $markup .= '<h5>Quelle</h5><p>';
            $markup .= strip_tags($metadata['Copyright']);
            $markup .= '</p>';
        }

        // Link zum Objekt
        $markup .= '<p><a target="_blank" rel="noopener" href="' .
            self::getItemLinkUrl($attachment, null) .
            '">Zum Objekt &gt;&gt;</a></p>';

        // Kurzbeschreibung
        if (isset($metadata['Kurzbeschreibung']) && !empty($metadata['Kurzbeschreibung'])) {
            $markup .= '<h5>Kurzbeschreibung</h5><div class="info-kurzbeschreibung">';
            $markup .= $metadata['Kurzbeschreibung'];
            $markup .= '</div>';
        }

        // shariff
        $allowedShareTerms = array(
            '[[license:CC-PD-M1]]',
            '[[license:CC-PD-U1]]',
            '[[license:CC-BY-3.0-DEU]]',
            '[[license:CC-BY-4.0-INT]]',
            '[[license:CC-BY-SA-3.0-DEU]]',
            '[[license:CC-BY-SA-4.0-INT]]',
            '[[license:G-NUG-KKN]]'
        );
        $mediaUrl = '';
        if (isset($attachment['file'])) {
            $mediaUrl = 'data-media-url="' . $attachment['file']->getWebPath('fullsize') . '"';
        }
        if (in_array($metadata['Rechtsstatus'], $allowedShareTerms)) {
            $markup .= '<div class="shariff"
                data-backend-url="null"
                data-button-style="icon"
                data-lang="de"'
                . $mediaUrl .
                'data-orientation="horizontal"
                data-services="[&quot;twitter&quot;,&quot;facebook&quot;,&quot;pinterest&quot;,&quot;tumblr&quot;]"
                data-theme="white"
                data-title="' . htmlentities($metadata['Titel']) . '"
                data-url="' . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http")
                    . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '#s' . $sectionCounter . '"
            ></div>';
        }

        // var_dump($attachment);
        return $markup;
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
     * Check if attachment-file is a zoomable image
     *
     * Will return the webpath to original file if there is
     * a zoomable image, else it will return false.
     *
     * With mixed media items it will return false.
     *
     * @param [array] $attachment
     * @return [String || boolean]
     */
    public static function getZoomable($attachment)
    {
        $result = false;
        if (!$attachment || !$attachment['item'] || !$attachment['file']) {
            return false;
        }
        $item = $attachment['item'];
        $files = $item->getFiles();
        $allowedExtensions = explode(',', 'image/gif,image/jpeg,image/pjpeg,image/png,image/tiff');
        $videoSrc = metadata($attachment['item'], array('Item Type Metadata', 'Videoquelle'));
        if (isset($videoSrc) && !empty($videoSrc)) {
            return false;
        }
        foreach ($files as $file) {
            if (!in_array($file['mime_type'], $allowedExtensions)) {
                return false;
            } else {
                $result = $file->getWebPath('original');
            }
        }
        return $result;
    }

    public static function isX3d($attachment)
    {
        if (!$attachment || !$attachment['item']) {
            return false;
        }
        $x3d = get_db()->getTable('X3d')->findByItemId($attachment{'item'}->id);
        if ($x3d) {
            return true;
        } else {
            return false;
        }

    }

    public static function getZoomData($attachment)
    {
        if (!$attachment) { return ''; }

        $file = $attachment['file'];
        $item = $attachment['item'];

        if ($file) {

            // $files = $item->getFiles();

            $zoomAttributes['data-zoom'] = self::getCompressedOriginalWebPath($file);
            $fileMetadata = json_decode($file['metadata']);

            if (is_object($fileMetadata) &&
                property_exists($fileMetadata, 'video') &&
                is_object($fileMetadata->video)) {

                if (property_exists($fileMetadata->video, 'resolution_x') &&
                    $fileMetadata->video->resolution_x > 0) {
                    $zoomAttributes['data-zoom-img-width'] = (string) $fileMetadata->video->resolution_x;
                }
                if (property_exists($fileMetadata->video, 'resolution_y') &&
                    $fileMetadata->video->resolution_y > 0) {
                    $zoomAttributes['data-zoom-img-height'] = (string) $fileMetadata->video->resolution_y;
                }
            }

            $s_options = array();
            if ($attachment['s_options'] && !empty($attachment['s_options'])) {
                $zoomAttributes['data-zoomdetail'] = (string) htmlspecialchars($attachment['s_options']);
            }
            return self::getAttributesFromArray($zoomAttributes);
        }

        return '';
    }

    public static function getCompressedOriginalWebPath($file)
    {
        $fileMetadata = null;
        if (isset($file['metadata'])) {
            $fileMetadata = json_decode($file['metadata']);
        }

        if (isset($fileMetadata) && !empty($fileMetadata) &&
            property_exists($fileMetadata, 'mime_type') &&
            $fileMetadata->mime_type === 'image/png' &&
            is_file(FILES_DIR . DIRECTORY_SEPARATOR .'original_compressed' .
                DIRECTORY_SEPARATOR . pathinfo($file->filename, PATHINFO_FILENAME) . '.webp'))
        {
            return WEB_FILES . '/original_compressed/' . pathinfo($file->filename, PATHINFO_FILENAME) . '.webp';

        } elseif (is_file(FILES_DIR . DIRECTORY_SEPARATOR . 'original_compressed' .
            DIRECTORY_SEPARATOR . $file->filename))
        {
            return WEB_FILES . '/original_compressed/' . $file->filename;

        } else {
            return $file->getWebPath();
        }
    }

    public static function getAttributesFromArray($array)
    {
        $result = '';
        foreach ($array as $key => $value) {
            if (!empty($result)) {
                $result .= ' ';
            }
            $result .= $key . '="' . $value . '"';
        }
        return $result;
    }

    /**
     * Get HTML (link to Colorbox and image) for attachments
     *
     * Used for main image displays in all templates
     *
     * @param array $attachment array with keys file, item, caption etc.
     * @return string HTML for linked item
     */
    public static function getAttachmentMarkup($attachment, $imgAttributes = array(), $imgZoom = false, $imageSize = 'fullsize')
    {
        if (!$attachment) { return ''; }
        $file = $attachment['file'];
        $item = $attachment['item'];
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
                $x3dDir = FILES_DIR . DIRECTORY_SEPARATOR . 'x3d'
                    . DIRECTORY_SEPARATOR . $x3d->directory;
                $x3dWebdir = WEB_FILES . '/x3d/' . $x3d->directory;
                $thn = '<img class="media-item-3d-thumb" '
                    . 'src="' . WEB_FILES . '/x3d/' . $x3d->directory
                    . '/or_' . $x3d->thumbnail . '" '
                    . 'alt="' . $attachmentTitle . '" '
                    . 'data-3durl="' . $x3dWebdir . '/' . $x3d->x3d_file . '"'
                    . '>';
                // $attachmentLinkUrl = record_url($attachment['item'], 'show', false);
                // $attachmentLinkUrl = exhibit_builder_exhibit_item_uri($attachment['item']);
                $x3dthn = '<div class="media-item-3d-thumb-container">' .
                    $thn .
                    '<img class="item-3d-thumb-icon" src="' .
                    img('menu_icon_3d_bg.svg') .
                    '" alt="3D"></div>';
                self::$currentAttechmentMediaType = '3d';
                return $x3dthn;
            }
        }

        // Video
        if($videoSrc) {
            if (isset($file)) {
                $videoImage = html_escape($file->getWebPath('fullsize'));
            } else {
                $videoImage = self::getVideoThumbnailFromShortcodeForMainItem($videoSrc, 'large', 'URL');
            }
            self::$currentAttechmentMediaType = 'video';
            return self::getVideoFromShortcode($videoSrc, $videoImage);
        }

        // add s_options
        if ($file) {
            $files = $item->getFiles();
            $fileMetadata = json_decode($files[0]['metadata']);

            if ($imgZoom === true) {

                $imgAttributes['title'] = false;
                $imgAttributes['data-zoom'] = self::getCompressedOriginalWebPath($files[0]);

                // ZOOM ATTRIBS
                if (is_object($fileMetadata) &&
                    property_exists($fileMetadata, 'video') &&
                    is_object($fileMetadata->video)) {

                    if (property_exists($fileMetadata->video, 'resolution_x') &&
                        $fileMetadata->video->resolution_x > 0) {
                        // value must be casted explicitly as string, as (stupid) tag_attributes() in globals.php checks for is_string()
                        $imgAttributes['data-zoom-img-width'] = (string) $fileMetadata->video->resolution_x;
                    }
                    if (property_exists($fileMetadata->video, 'resolution_y') &&
                        $fileMetadata->video->resolution_y > 0) {
                        // value must be casted explicitly as string, as (stupid) tag_attributes() in globals.php checks for is_string()
                        $imgAttributes['data-zoom-img-height'] = (string) $fileMetadata->video->resolution_y;
                    }
                }

                // ZOOM OPTIONS
                $s_options = array();
                if ($attachment['s_options'] && !empty($attachment['s_options'])) {
                    $imgAttributes['data-zoomdetail'] = (string) $attachment['s_options'];
                }

            }

            // image & general
            $fileOptions = array(
                'imageSize' => 'fullsize',
                'linkToFile' => false,
                'imgAttributes'=> $imgAttributes
            );

            if ($imageSize === 'middsize' && is_readable(FILES_DIR . '/' . $file->getStoragePath('middsize'))) {
                $fileOptions['imageSize'] = 'middsize';
            }


            // audio
            $audioTypes = ['audio/mpeg', 'audio/ogg'];
            $isAudio = false;
            foreach ($files as $audio) {
                if (in_array($audio->mime_type, $audioTypes)) {
                    $isAudio = true;
                    break;
                }
            }
            if ($isAudio) {
                $audioImage = '';
                $html = '<audio controls="" class="audio-controls">';
                foreach ($files as $audio) {
                    if (in_array($audio->mime_type, $audioTypes)) {
                        $html .= file_markup($audio, $fileOptions, null);
                    } else {
                        $fileOptions['imgAttributes'] = [
                            'class' => 'media-audio-image',
                            'title' => false
                        ];
                        $audioImage = file_markup($audio, $fileOptions, ['class' => 'media-audio-image-container']);
                    }
                }
                self::$currentAttechmentMediaType = 'audio';
                return $audioImage. $html . '</audio>';
            }

            // image
            self::$currentAttechmentMediaType = 'image';

            // gif
            if (property_exists($fileMetadata, 'mime_type') &&
                $fileMetadata->mime_type === 'image/gif')
            {
                $fileOptions['imageSize'] = 'original';
            }

            if (property_exists($fileMetadata, 'mime_type') &&
                $fileMetadata->mime_type === 'image/png')
            {
                $info = pathinfo($file->filename);
                if (is_file(FILES_DIR . '/' . $fileOptions['imageSize'] . '/' . $info['filename'] . '.webp')) {
                    $html = '<picture>' .
                        '<source srcset="' . WEB_FILES . '/' . $fileOptions['imageSize'] . '/' . $info['filename'] . '.webp' . '" type="image/webp">' .
                        '<source srcset="' . WEB_FILES . '/original/' . $file->filename . '" type="image/png">';
                    $fileOptions['imageSize'] = 'original';
                    $html .= file_markup($file, $fileOptions, null) .
                        '</picture>';
                    return $html;
                } else {
                    $fileOptions['imageSize'] = 'original';
                }
            }
            return file_markup($file, $fileOptions, null);
        }
        // END add s_options

        // empty object with no files attached
        return link_to_item($attachmentTitle, ['target' => '_blank', 'rel' => 'noopener'], 'show', $item);
    }

    /**
     * Find an item in exhibit pages by item id
     *
     * @param int $itemId Object Item ID
     * @return array Array of ExhibitPage objects
     */
    public static function findItemInExhibitPage($itemId)
    {
        $pages = array();
        $entries = get_db()->getTable('ExhibitPageEntry')->findBy(array('item_id' => $itemId));
        if (isset($entries) && is_array($entries)) {
            foreach ($entries as $entry) {
                $pages[] = get_db()->getTable('ExhibitPage')->find($entry->page_id);
            }
        }
        return $pages;
    }

    /**
     * Get HTML list link to edit an exhibit page
     *
     * @param ExhibitPage $page An ExhibitPage object
     * @return String HTML output
     */
    public static function getEditPageEntry($page)
    {
        $pageId = html_escape($page->id);
        $html = '<li class="page" id="page_' . $pageId . '">'
            . '<a href="exhibits/edit-page-content/' . $pageId . '">'
            . html_escape($page->title)
            . '</a>'
            . '</li>';
        return $html;
    }

    /**
     * Get available colors of a palette
     *
     * @param String $palette Name of the palette
     * @return Array All colors of a palette
     */
    public static function getColorsFromExhibitColorPalette($palette)
    {
        $colors = array();
        if (!isset($palette) || empty($palette) || !is_string($palette)) {
            return $colors;
        }
        $ExhibitColorPalette = get_db()->getTable('ExhibitColorPalette');
        if (!isset($ExhibitColorPalette) || !is_object($ExhibitColorPalette)) {
            return $colors;
        }
        $colorPalettes = $ExhibitColorPalette->findBy(array('palette' => $palette));
        if (!is_array($colorPalettes)) {
            return $colors;
        }
        foreach ($colorPalettes as $color) {
            $colors[$color['color']] = $color->toArray();
        }
        return $colors;
    }

    public static function getMenuColor($colors)
    {
        $menuColor = ['type' => 'dark', 'hex' => '#447494'];
        if (is_array($colors)) {
            foreach ($colors as $color) {
                if ($color['menu'] === 1) {
                    $menuColor = [
                        'type' => $color['type'],
                        'hex' => $color['hex']
                    ];
                }
            }
        }
        return $menuColor;
    }

    public static function getSpaCss($menuColor, $exhibitType, $navcolor)
    {
        $chapterRGB = implode(',', sscanf($menuColor['hex'], "#%02x%02x%02x"));
        $markup = '<style type="text/css">';
        if ($exhibitType === 'litfass_ddb') {
            if ($navcolor === 'dark') {
                $markup .= '.menu-container .menu li.chapter {background-color:rgb(255,255,255);}';
            } else {
                $markup .= '.menu-container .menu li.chapter {background-color:#f7e8ed;}';
            }
            $markup .= '.menu-container .menu .chapter a {color:#1d1d1b;}';
        } else {
            $markup .= '.menu-container .menu li.chapter {background-color:rgba(' . $chapterRGB . ',0.4);}';
        }
        $markup .= '.menu-container .menu li.active,.menu-container .menu li.active.chapter {background-color:' . $menuColor['hex'] . ';}';
        if ($menuColor['type'] === 'dark') {
            $markup .= '.menu-container .menu li.active a {color:#fff;}';
            $markup .= '.menu-container .menu li .menu-box.menu-number {background-color:' . $menuColor['hex'] . ';color:#fff;}';
        } else {
            $markup .= '.menu-container .menu li.active a {color:#1d1d1b;font-weight:700;}';
            $markup .= '.menu-container .menu li .menu-box.menu-number {background-color:' . $menuColor['hex'] . ';color:#1d1d1b;}';
        }
        $markup .= '</style>';
        return $markup;
    }

    /**
     * Get available colornames of a palette
     *
     * @param String $palette Name of the palette
     * @return Array All colornames of a palette
     */
    public static function getColornamesFromExhibitColorPalette($palette)
    {
        $colors = array();
        if (!isset($palette) || empty($palette) || !is_string($palette)) {
            return $colors;
        }
        $ExhibitColorPalette = get_db()->getTable('ExhibitColorPalette');
        if (!isset($ExhibitColorPalette) || !is_object($ExhibitColorPalette)) {
            return $colors;
        }
        $colorPalettes = $ExhibitColorPalette->findBy(array('palette' => $palette));
        if (!is_array($colorPalettes)) {
            return $colors;
        }
        foreach ($colorPalettes as $color) {
            $colors[$color['color']] = $color['color'];
        }
        return $colors;
    }

    /**
     * Get the HTML for an item attachment on a layout form.
     *
     * @param int $order The index of this layout element.
     * @return string
     */
    public static function exhibit_builder_layout_form_item($order, $getOptions = null)
    {
        $attachment = self::exhibit_builder_page_attachment($order);
        $item = null;
        $file = null;
        $caption = null;
        $s_options = null;

        if ($attachment) {
            $item = $attachment['item'];
            if ($attachment['file_specified']) {
                $file = $attachment['file'];
            }
            $caption = $attachment['caption'];
            if ($attachment['s_options']) {
                $s_options = $attachment['s_options'];
            } else {
                $s_options = '';
            }
        }

        return self::exhibit_builder_form_attachment($item, $file, $caption, $order, $s_options);
    }

    /**
     * Return the data for an attached item/file.
     *
     * @param int $entryIndex Page entry index, defaults to 1
     * @param int $fallbackFileIndex File index to choose if no file was picked
     *  specifically. Defaults to 0, the first file for the item
     * @param ExhibitPage|null Page to use, if null, the current page is used
     * @return array|null Null if no such entry exists. If one does, returns an
     *  array, with the following keys:
     *   * item: the attached Item object
     *   * file: the File to be displayed (null if no file exists)
     *   * file_specified: boolean, whether the file was user-selected or auto-picked
     *   * caption: a string, the attachment's caption
     */
    public static function exhibit_builder_page_attachment($entryIndex = 1, $fallbackFileIndex = 0, $exhibitPage = null)
    {
        if (!$exhibitPage) {
            $exhibitPage = get_current_record('exhibit_page');
        }

        $entries = $exhibitPage->ExhibitPageEntry;

        if (!isset($entries[$entryIndex])) {
            return null;
        }

        $entry = $entries[$entryIndex];

        $item = null;
        $file = null;
        $file_specified = false;
        $caption = null;
        $s_options = null;

        if (($item = $entry->Item)) {
            if (($file = $entry->File)) {
                $file_specified = true;
            } else if (isset($item->Files[$fallbackFileIndex])) {
                $file = $item->Files[$fallbackFileIndex];
            }
        } else {
            // If there's no item, nothing is attached.
            return null;
        }

        $caption = $entry->caption;
        $s_options = $entry->s_options;

        return compact(array('item', 'file', 'file_specified', 'caption', 's_options'));
    }

    /**
     * Get the HTML for "attach an item" section of the exhibit form
     *
     * @param Item $item The currently attached item, if any
     * @param File $file The currently attached file, if any
     * @param string|boolean $caption The current caption. If false, don't display the caption form.
     * @param int $order Layout form order. If omitted, don't output form elements
     * @return string
     */
    public static function exhibit_builder_form_attachment($item = null, $file = null, $caption = null, $order = null, $s_options = null)
    {

        if ($item) {
            $html = '<div class="item-select-outer exhibit-form-element" data-item-id="' . $item->id . '">'
                  . '<div class="item-select-inner">'
                  . '<h4 class="title">'
                  . metadata($item, array('Dublin Core', 'Title'))
                  . '</h4>';
            if (metadata($item, 'has files')) {
                if ($file) {
                    $html .= '<div class="item-file">'
                        . file_image('square_thumbnail', array(), $file)
                        . '</div>';
                } else {
                    foreach ($item->Files as $displayFile) {
                        if ($displayFile->hasThumbnail()) {
                            $html .= '<div class="item-file">'
                                . file_image('square_thumbnail', array(), $displayFile)
                                . '</div>';
                        }
                    }
                }
                if ($order) {
                    $html .= exhibit_builder_form_file($order, $item, $file);
                }
            }

            if ($caption !== false) {
                $html .= exhibit_builder_form_caption($order, $caption);
            }

            if ($s_options !== false) {
                $html .= self::exhibit_builder_form_s_options($order, $item, $s_options);
            }

            $html .= '</div>' . "\n";
        } else {
            $html = '<div class="item-select-outer exhibit-form-element">'
                  . '<p class="attach-item-link">'
                  . __('There is no item attached.')
                  . ' <a href="#" class="green button">'
                  . __('Attach an Item') .'</a></p>' . "\n";
        }

        // If an order was passed, this is an input on a layout form, so include the
        // form element to indicate what file is attached here.
        if ($order) {
            $itemId = ($item) ? $item->id : null;
            $html .= get_view()->formHidden("Item[$order]", $itemId);
        }

        $html .= '</div>';
        return $html;
    }

    /**
     * Get the HTML for option form input.
     *
     * @param int $order The order of the attachment for this options
     * @param string $options The existing options, if any
     * @return string
     */
    public static function exhibit_builder_form_s_options($order, $item, $options = null)
    {
        $label = __('Zoom');
        ob_start();
        require dirname(__FILE__) . DIRECTORY_SEPARATOR . 'templates' .
            DIRECTORY_SEPARATOR . 'tpl_s_options.php';
        $html = ob_get_clean();
        // $html =
        return $html;
    }

    public static function getInstitutions($raw)
    {
        $institutions = unserialize($raw);
        if (!$institutions) {
            $institutions = [];
        }
        uasort($institutions, array('self', 'cmpInstitutions'));
        $institutions = self::sanitizeInstitutions($institutions);
        return $institutions;
    }

    public static function cmpInstitutions($a, $b)
    {
        if (!isset($a['pos'])) {
            return -1;
        }
        if (!isset($b['pos'])) {
            return 1;
        }
        if ($a['pos'] == $b['pos']) {
            return 0;
        }
        return ($a['pos'] < $b['pos']) ? -1 : 1;
    }

    public static function sanitizeInstitutions($institutions)
    {
        $result = [];
        foreach ($institutions as $key => $value) {
            if (!empty($value['name'])) {
                $result[$key] = $value;
            }
        }
        return $result;
    }

    public static function getOriginalImageUrl($attachment)
    {
        $url = '';
        if (isset($attachment['file']) && !empty($attachment['file'])) {
            $url = $attachment['file']->getWebPath('original');
        }
        return $url;
    }

    public static function getFullsizeImageUrl($attachment)
    {
        $url = '';
        if (isset($attachment['file']) && !empty($attachment['file'])) {
            if ($attachment['file']->mime_type === 'image/gif') {
                $url = $attachment['file']->getWebPath('original');
            } elseif ($attachment['file']->mime_type === 'image/png') {
                $url = $attachment['file']->getWebPath('fullsize');
                $ext = pathinfo($url, PATHINFO_EXTENSION);
                $url = preg_replace('/' . $ext . '$/', 'webp', $url);
            } else {
                $url = $attachment['file']->getWebPath('fullsize');
            }
        }
        return $url;
    }

    public static function getLeadingZeroNum(int $num)
    {
        return $num < 10 ? '0' . $num : $num;
    }

    /**
     * Set section anchors in summary
     *
     * @param string $sectionAnchors
     * @param int $sectionCounter
     * @return string $sectionAnchors
     */
    public static function setSectionAnchors($sectionAnchors, $sectionCounter)
    {
        $sectionAnchors = (empty($sectionAnchors))? '' : $sectionAnchors . ', ';
        $sectionAnchors .= "'s" . $sectionCounter . "'";
        return $sectionAnchors;
    }

    /**
     * Set section colors in summary templates
     *
     * @param string $sectionColors
     * @param string $color
     * @return string $sectionColors
     */
    public static function setSectionColors($sectionColors, $color)
    {
        $color = self::checkColorInPalette($color);
        $sectionColors = (empty($sectionColors))? '' : $sectionColors . ',';
        $sectionColors .= $color;
        return $sectionColors;
    }

    /**
     * Check if selectd color is in color palette
     *
     * If color is not in palette return fallback menu color.
     *
     * @param string $color
     * @return string $color
     */
    public static function checkColorInPalette($color)
    {
        if (!isset(self::$colorPalettes) || empty(self::$colorPalettes)) {
            self::setColorPalettes();
        }
        $colorParts = explode('.', $color);
        $mainColor = 'white';

        foreach (self::$colorPalettes as $palette) {
            if ($palette->palette === $colorParts[1] &&
                $palette->color === $colorParts[2]
            ) {
                return $color;
            } elseif ($palette->palette === $colorParts[1] &&
                $palette->menu == 1) {
                $mainColor = $palette->color;
            }
        }
        return $colorParts[0] . '.' .
            $colorParts[1] . '.' .
            $mainColor . '.' .
            $colorParts[3];
    }

    /**
     * Set self::$colorPalettes from db
     *
     * @return void
     */
    public static function setColorPalettes()
    {
        $db = get_db();
        self::$colorPalettes = $db->getTable('ExhibitColorPalette')->findAll();
    }

    public static function getImprint($exhibitType, $replacements, $title)
    {
        $replacements = unserialize($replacements);
        $filesDir = realpath($_SERVER['DOCUMENT_ROOT'] . '/../data');
        $masterDoc = file_get_contents($filesDir . '/imprint_' . $exhibitType . '.html');
        $result = preg_replace_callback(
            '/(\[\[)([^\]\:]+)::([^\]]+)(\]\])/',
            function($matches) use ($replacements, $title) {
                if ($matches[2] === 'titleExhibit') {
                    return $title;
                } elseif (is_array($replacements) && array_key_exists($matches[2], $replacements)) {
                    return $replacements[$matches[2]];
                } else {
                    return '';
                }
            },
            $masterDoc
        );
        return $result;
    }

    public static function getInstitutionsHtml($institutions)
    {
        if (!isset($institutions) || !is_array($institutions)) {
            $institutions = array();
        }
        $html = '';
        foreach ($institutions as $institution) {
            if (!empty($institution['name'])) {
                if (!empty($html)) {
                    $html .= '<br>';
                }
                $html .= $institution['name'];
            }
        }
        return $html;
    }


    public static function getBrowserLanguage()
    {
        $acceptLang = ['de', 'en'];
        foreach (explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']) as $lang) {
            $pattern = '/^(?P<primarytag>[a-zA-Z]{2,8})'.
            '(?:-(?P<subtag>[a-zA-Z]{2,8}))?(?:(?:;q=)'.
            '(?P<quantifier>\d\.\d))?$/';
            $splits = array();
            if (preg_match($pattern, $lang, $splits)) {
                if (in_array($splits['primarytag'], $acceptLang)) {
                    return $splits['primarytag'];
                }
            }
        }
        return 'en';

    }

}
