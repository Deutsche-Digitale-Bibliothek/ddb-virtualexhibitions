<?php
/**
 * Generate Webp derrivates
 * @copyright Copyright 2020 Viktor Grandgeorg
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 */

/**
 * Webp
 */
class Webp
{

    public $basePath = '';

    public function __construct($basePath = '')
    {
        if (isset($basePath) && !empty($basePath)) {
            $this->basePath = $basePath;
        } elseif (defined('FILES_DIR')) {
            $this->basePath = FILES_DIR;
        }
    }

    public function run($sourcePath, $destPath, $options)
    {
        if ($this->isCwebpInstalled()) {

            // strip ext
            $destExt = pathinfo($destPath, PATHINFO_EXTENSION);
            $destPath = preg_replace('/' . $destExt . '$/', '', $destPath);

            // orig ext
            $sourceExt = pathinfo($sourcePath, PATHINFO_EXTENSION);

            // resize
            $this->resize($sourcePath, $destPath, $sourceExt, $options);

            // gen webp
            $webp = $this->getWebpCommand(
                $destPath . $sourceExt,
                $destPath . 'webp',
                $options['webp_quality']
            );
            $output = array();
            $retval = false;
            exec($webp, $output, $retval);
            if (0 !== $retval) {
                // log
            }

            // unlink resized
            unlink($destPath . $sourceExt);

            // move
            if (isset($options['type'])) {
                $this->move(
                    $destPath . 'webp',
                    pathinfo($sourcePath, PATHINFO_FILENAME) . '.webp',
                    $options['type']
                );
            }

            // original compressed
            if (isset($options['type']) && $options['type'] === 'fullsize') {
                $this->setOrigCompressedDir();
                $origCompressedPath = preg_replace('/' . $sourceExt . '$/', 'webp', $sourcePath);
                $webp = $this->getWebpCommand(
                    $sourcePath,
                    $origCompressedPath,
                    $options['webp_quality']
                );
                exec($webp);
                $this->move(
                    $origCompressedPath,
                    pathinfo($sourcePath, PATHINFO_FILENAME) . '.webp',
                    'original_compressed'
                );
            }

        }
    }

    protected function isCwebpInstalled()
    {
        $output = array();
        $retval = false;
        exec('cwebp -version', $output, $retval);
        if ($retval !== 0) {
            return false;
        }
        return true;
    }

    protected function resize($sourcePath, $destPath, $ext, $options)
    {
        if(extension_loaded('imagick')) {
            $img = new Imagick($sourcePath);

            $profiles = $img->getImageProfiles('icc', true);
            $img->stripImage();
            if (isset($profiles) && !empty($profiles) && isset($profiles['icc'])) {
                $img->profileImage('icc', $profiles['icc']);
            }

            if ($options['resize_square'] === '1') {
                $img->cropThumbnailImage(
                    $options['resize_width'],
                    $options['resize_width']
                );
            } else {

                $srcWidth = $img->getImageWidth();
                $srcHeight = $img->getImageHeight();

                if ($srcWidth > $options['resize_width'] ||
                    $srcHeight > $options['resize_height'])
                {
                    $targetWidth = $options['resize_width'];
                    $targetHeight = $options['resize_height'];
                } else {
                    $targetWidth = $srcWidth;
                    $targetHeight = $srcHeight;
                }

                $img->resizeImage(
                    $targetWidth,
                    $targetHeight,
                    Imagick::FILTER_LANCZOS, 1, true
                );
            }

            $img->writeImage($destPath . $ext);
            $img->clear();
            $img->destroy();
        } else {
            copy($sourcePath, $destPath . $ext);
        }
    }

    protected function getWebpCommand($in, $out, $quality)
    {
        return 'cwebp'
        . ' -q '  . $quality . ' '
        . $in
        . ' -o '
        . $out
        . ' 2>&1';
    }

    protected function move($from, $filename, $type)
    {
        $to = $this->basePath . '/' . $type . '/' . $filename;
        rename($from, $to);
    }

    protected function setOrigCompressedDir()
    {
        if (!is_dir($this->basePath . '/original_compressed')) {
            mkdir($this->basePath . '/original_compressed', 0775);
        }
    }

}
