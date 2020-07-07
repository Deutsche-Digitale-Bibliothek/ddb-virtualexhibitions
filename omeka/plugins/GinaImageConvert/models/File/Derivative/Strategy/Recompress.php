<?php
/**
 * Image Convert
 *
 * @copyright Copyright 2020 Copyright Grandgeorg Websolutions
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 */

/**
 * Strategy for making derivatives with jpeg recompress on the command line.
 *
 * @package @package Omeka\Plugins\GinaImageConvert
 */
class File_Derivative_Strategy_Recompress extends Omeka_File_Derivative_AbstractStrategy
{
    protected $recompressInstalled = null;
    public $recompressOptions = null;

    public function createImage($sourcePath, $destPath, $type, $sizeConstraint, $mimeType)
    {
        if (($convertPath = get_option('path_to_convert'))) {
            $this->setOptions(array('path_to_convert' => $convertPath));
        }
        if ($type == 'thumbnail') {
            $type = 'thumbnails';
        } elseif ($type == 'square_thumbnail') {
            $type = 'square_thumbnails';
        }
        $this->setRecompressInstalled();
        $this->setRecompressOptions();

        // $data = 'hit -' .
        //     ' $sourcePath: ' . $sourcePath .
        //     ' $destPath: ' . $destPath .
        //     ' $type: ' . $type .
        //     ' $sizeConstraint: ' . $sizeConstraint .
        //     ' $mimeType: ' . $mimeType .
        //     ' options: ' . implode(',' . $this->getOptions()) .
        //     ' recompressOptions: ' . serialize($this->recompressOptions) .
        //     "\n";
        // file_put_contents(__DIR__ . '/log.log', $data, FILE_APPEND);

        if ($mimeType !== 'image/jpeg' || !$this->recompressInstalled) {
            return $this->legacyCreate($sourcePath, $destPath, $type, $sizeConstraint, $mimeType);
        }

        if ($type === 'fullsize') {
            $this->compressOriginal($sourcePath);
        }

        return $this->compressSized($sourcePath, $destPath, $type);

    }

    protected function legacyCreate($sourcePath, $destPath, $type, $sizeConstraint, $mimeType)
    {
        $legacy = new Omeka_File_Derivative_Strategy_ExternalImageMagick();
        $legacy->setOptions($this->getOptions());
        if (!$legacy->createImage($sourcePath, $destPath, $type, $sizeConstraint, $mimeType)) {
            return false;
        }
        return true;
    }

    protected function setRecompressOptions()
    {
        if (!isset($this->recompressOptions)) {
            $params = $this->getDefaultConfig();
            $options = unserialize(get_option('gina_image_convert'));
            if (isset($options) && !empty($options) && $options !== false) {
                $this->recompressOptions = $this->mergeOptions($params, $options);
            } else {
                $this->recompressOptions = $params;
            }
        }
    }

    public function mergeOptions($params, $options)
    {
        $result = array();
        foreach ($params as $sizeKey => $sizeParams) {
            foreach ($sizeParams as $key => $param) {
                if (!isset($options[$sizeKey][$key]) ||
                    (empty($options[$sizeKey][$key]) && $options[$sizeKey][$key] !== 0)
                ) {
                    $result[$sizeKey][$key] = $param;
                } else {
                    $result[$sizeKey][$key] = $options[$sizeKey][$key];
                }
            }
        }
        return $result;
    }

    protected function getDefaultConfig()
    {
        return require dirname(__FILE__) . '/../../../../default_config.php';
    }

    protected function setRecompressInstalled()
    {
        if (!isset($this->recompressInstalled)) {
            $this->recompressInstalled = $this->isRecompressInstalled();
        }
    }

    protected function isRecompressInstalled()
    {
        $output = array();
        $retval = false;
        exec('which jpeg-recompress', $output, $retval);
        if ($retval !== 0 || !isset($output[0]) || empty($output[0])) {
            return false;
        }
        return true;
    }

    public function compressSized($sourcePath, $destPath, $type)
    {

        if (!isset($this->recompressOptions[$type])) {
            _log('Image type ' . $type . ' not configured for recompression.',
                Zend_Log::ERR);
            return false;
        }

        $this->resizeImage($sourcePath, $destPath, $type);
        $recompress = $this->getRecompressCommand($destPath, $destPath, $type);
        $output = array();
        $retval = false;
        exec($recompress, $output, $retval);

        if ($retval !== 0) { return false; }
        return true;
    }

    public function compressOriginal($sourcePath)
    {
        $type = 'original';
        if (!isset($this->recompressOptions[$type])) {
            _log('Image type ' . $type . ' not configured for recompression.',
                Zend_Log::ERR);
            return false;
        }
        $this->checkOriginalCompressedDir(FILES_DIR . '/original_compressed/');
        $destPath = FILES_DIR . '/original_compressed/' . pathinfo($sourcePath, PATHINFO_BASENAME);
        copy($sourcePath, $destPath);

        $recompress = $this->getRecompressCommand($destPath, $destPath, $type);
        $output = array();
        $retval = false;
        exec($recompress, $output, $retval);

        if ($retval !== 0) { return false; }
        return true;
    }

    public function checkOriginalCompressedDir($dir)
    {
        if (!is_dir($dir)) {
            mkdir($dir, 0755);
        }
    }

    public function resizeImage($sourcePath, $destPath, $type)
    {
        if(extension_loaded('imagick')) {
            $img = new Imagick($sourcePath);

            // remove Exif etc. but keep ICC
            $profiles = $img->getImageProfiles('icc', true);
            $img->stripImage();
            if (isset($profiles) && !empty($profiles) && isset($profiles['icc'])) {
                $img->profileImage('icc', $profiles['icc']);
            }

            $quality = $img->getImageCompressionQuality();
            if ($quality === 0 ||
                $quality > (int) $this->recompressOptions[$type]['resize_max_quality']
            ) {
                $quality = (int) $this->recompressOptions[$type]['resize_max_quality'];
            }

            if ($this->recompressOptions[$type]['resize_square'] === '1') {
                $img->cropThumbnailImage(
                    $this->recompressOptions[$type]['resize_width'],
                    $this->recompressOptions[$type]['resize_width']
                );
            } else {
                $img->resizeImage(
                    $this->recompressOptions[$type]['resize_width'],
                    $this->recompressOptions[$type]['resize_height'],
                    Imagick::FILTER_LANCZOS, 1, true
                );
            }

            $img->setImageCompression(Imagick::COMPRESSION_JPEG);
            $img->setImageCompressionQuality($quality);

            $img->writeImage($destPath);
            $img->clear();
            $img->destroy();
        } else {
            copy($sourcePath, $destPath);
        }
    }

    public function getRecompressCommand($in, $out, $type)
    {
        // Do not use --strip option, as it will remove ICC profiles'
        return 'jpeg-recompress'
        . ' --target '   . $this->recompressOptions[$type]['recompress_target']
        . ' --min '      . $this->recompressOptions[$type]['recompress_min']
        . ' --max '      . $this->recompressOptions[$type]['recompress_max']
        . ' --loops '    . $this->recompressOptions[$type]['recompress_loops']
        . ' --method '   . $this->recompressOptions[$type]['recompress_method']
        . ' --accurate '
        . $in
        . ' '
        . $out
        . ' 2>&1';
    }
}