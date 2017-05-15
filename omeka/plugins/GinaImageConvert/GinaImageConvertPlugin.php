<?php
/**
 * Image Convert
 *
 * Keeps original names of files and put them in a hierarchical structure.
 *
 * @copyright Copyright Grandgeorg Websolutions 2017
 * @license GPLv3
 * @package ImageConvert
 */

/**
 * The ImageConvert plugin.
 * @package Omeka\Plugins\ImageConvert
 */
class GinaImageConvertPlugin extends Omeka_Plugin_AbstractPlugin
{
    protected $_hooks = array(
        'before_save_file'
    );

    protected static $convertedFiles = array();


    public function hookBeforeSaveFile($args)
    {
        if (isset($args['record'])
            && property_exists($args['record'], 'filename')
            && !empty($args{'record'}->filename)
            && !$args{'record'}->stored)
        {
            // $dir = $args{'record'}->getStorage()->getTempDir();
            // $file = $dir . '/' . $args{'record'}->filename;
            $file = $args{'record'}->getPath('original');
            if (isset($file) && !empty($file)) {
                $this->removeExif($file, false);
            }
        }
    }


    /**
     * @param $file string absolute path to file
     * @param $log boolean Log / Debug on or off
     * @return void
     */
    public function removeExif($file, $log = false)
    {
        if (!in_array($file, self::$convertedFiles)) {
            if (is_readable($file)) {
                $allowedExtensions = array('jpg', 'jpeg');
                if (in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), $allowedExtensions)) {

                    if(extension_loaded('imagick')) {
                        $img = new Imagick($file);
                        $profiles = $img->getImageProfiles('icc', true);
                        $img->stripImage();
                        if (isset($profiles) && !empty($profiles) && isset($profiles['icc'])) {
                            $img->profileImage('icc', $profiles['icc']);
                        }
                        $img->writeImage($file);
                        $img->clear();
                        $img->destroy();
                        self::$convertedFiles[] = $file;

                        if ($log === true) {
                            $this->_log($file, 'INFO', ' File successfully converted');
                        }
                    } elseif ($log === true) {
                        $this->_log($file, 'WARNING', 'imagick extension not loaded');
                    }
                } elseif ($log === true) {
                    $this->_log($file, 'WARNING', 'Extension not allowed');
                }
            } elseif ($log === true) {
                $this->_log($file, 'ERROR', 'File is not readable');
            }
        } elseif ($log === true) {
            $this->_log($file, 'INFO', 'File already converted');
        }
    }

    /**
     * @param $file string absolute path to file
     * @param $type string INFO | WARNING | ERROR
     * @param $file msg Log messsage
     * @return void
     */
    protected function _log($file, $type, $msg)
    {
        file_put_contents(__DIR__ . '/log.txt', date('Y-m-d H.i:s') . ' ' . $type . ': ' . $file . ' ' . $msg . "\n", FILE_APPEND);
    }


}
