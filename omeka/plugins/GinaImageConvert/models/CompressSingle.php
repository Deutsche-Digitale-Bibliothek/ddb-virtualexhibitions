<?php
/**
 * Simple Compressor for single files
 * @copyright Copyright 2020 Viktor Grandgeorg
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 */

/**
 * Compressor.
 *
 */
class CompressSingle
{
    /**
     * config
     *
     * In addition to defaults following keys can be set:
     * 'resize' bool Weather or not to resize the image first.
     * 'compress' string can be one of:
     *      'jpg' use jpeg recompress
     *      'png' use webp
     *      'auto' detect compression by file extension
     *
     * @var array
     */
    protected $config;
    protected $defaults = [
        'resize_max_quality'    => 100,
        'resize_width'          => 1920,
        'resize_height'         => 1080,
        'resize_square'         => false,
        'recompress_target'     => 0.9999,
        'recompress_min'        => 40,
        'recompress_max'        => 95,
        'recompress_loops'      => 6,
        'recompress_method'     => 'ssim',
        'webp_quality'          => 75
    ];

    public function run($in, $outDir, $config)
    {
        if (!is_file($in)) { return false; }
        if (!is_dir($outDir) && !mkdir($outDir, 0775)) { return false; }

        $this->config = array_merge($this->defaults, $config);

        $ext = strtolower(pathinfo($in, PATHINFO_EXTENSION));
        $filename = pathinfo($in, PATHINFO_FILENAME);

        $isResized = false;
        if ($this->config['resize'] && isset($this->config['compress'])) {
            $out = $outDir . DIRECTORY_SEPARATOR . '_temp_' . $filename . '.' . $ext;
            if (!$this->resize($in, $out, $ext, $filename)) {
                return false;
            } else {
                $isResized = true;
                $in = $out;
            }
        }

        if (isset($this->config['compress']) &&
            ($this->config['compress'] === 'jpg' ||
                ($this->config['compress'] === 'auto' && in_array($ext, array('jpg', 'jpeg')))))
        {

            $out = $outDir . DIRECTORY_SEPARATOR . $filename . '.' . $ext;
            if (!$this->compressJpg($in, $out)) { return false; }

        } elseif (isset($this->config['compress']) &&
            ($this->config['compress'] === 'png' ||
                ($this->config['compress'] === 'auto' && $ext === 'png')))
        {

            $out = $outDir . DIRECTORY_SEPARATOR . $filename . '.webp';
            if (!$this->compressWebp($in, $out)) { return false; }

        } else {

            $out = $outDir . DIRECTORY_SEPARATOR . $filename . '.' . $ext;
            if (!copy($in, $out)) { return false; }

        }

        if ($isResized) { unlink($in); }
        return true;
    }

    protected function resize($in, $out, $ext, $filename)
    {
        if(extension_loaded('imagick')) {
            $img = new Imagick($in);

            $profiles = $img->getImageProfiles('icc', true);
            $img->stripImage();
            if (isset($profiles) && !empty($profiles) && isset($profiles['icc'])) {
                $img->profileImage('icc', $profiles['icc']);
            }

            $quality = $img->getImageCompressionQuality();
            if ($quality === 0 || $quality > (int) $this->config['resize_max_quality']) {
                $quality = (int) $this->config['resize_max_quality'];
            }

            if ($this->config['resize_square']) {
                $img->cropThumbnailImage(
                    $this->config['resize_width'],
                    $this->config['resize_width']);
            } else {
                $srcWidth = $img->getImageWidth();
                $srcHeight = $img->getImageHeight();
                if ($srcWidth > $this->config['resize_width'] ||
                    $srcHeight > $this->config['resize_height'])
                {
                    $targetWidth = $this->config['resize_width'];
                    $targetHeight = $this->config['resize_height'];
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

            if ($ext === 'jpg' || $ext === 'jpeg') {
                $img->setImageCompression(Imagick::COMPRESSION_JPEG);
                $img->setImageCompressionQuality($quality);
            }

            $img->writeImage($out);
            $img->clear();
            $img->destroy();

            return true;
        } else {
            return false;
        }
    }

    protected function compressJpg($in, $out)
    {
        if (!$this->isRecompressInstalled()) {
            return false;
        }

        $command = 'jpeg-recompress'
        . ' --target '   . $this->config['recompress_target']
        . ' --min '      . $this->config['recompress_min']
        . ' --max '      . $this->config['recompress_max']
        . ' --loops '    . $this->config['recompress_loops']
        . ' --method '   . $this->config['recompress_method']
        . ' --accurate '
        . $in
        . ' '
        . $out
        . ' 2>&1';

        $output = array();
        $retval = 0;
        exec($command, $output, $retval);

        if (0 !== $retval) {
            return false;
        } else {
            return true;
        }
    }

    protected function isRecompressInstalled()
    {
        $output = array();
        $retval = 0;
        exec('which jpeg-recompress', $output, $retval);
        if ($retval !== 0 || !isset($output[0]) || empty($output[0])) {
            return false;
        }
        return true;
    }

    protected function compressWebp($in, $out)
    {
        if (!$this->isCwebpInstalled()) {
            return false;
        }

        $command = 'cwebp'
        . ' -q '  . $this->config['webp_quality'] . ' '
        . $in
        . ' -o '
        . $out
        . ' 2>&1';

        $output = array();
        $retval = 0;
        exec($command, $output, $retval);

        if (0 !== $retval) {
            return false;
        } else {
            return true;
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
}

