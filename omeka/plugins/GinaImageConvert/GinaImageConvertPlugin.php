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
                $this->removeExif($file);
            }
        }
    }


    /**
     * @param $file absolute path to file
     * @return void
     */
    public function removeExif($file)
    {
        if (is_readable($file)) {
            $allowedExtensions = array('jpg', 'jpeg');
            if (in_array(pathinfo($file, PATHINFO_EXTENSION), $allowedExtensions)) {
                $img = new Imagick($file);
                $profiles = $img->getImageProfiles('icc', true);
                $img->stripImage();
                if (isset($profiles) && !empty($profiles) && isset($profiles['icc'])) {
                    $img->profileImage('icc', $profiles['icc']);
                }
                $img->writeImage($file);
                $img->clear();
                $img->destroy();
            }
        }
    }


}
