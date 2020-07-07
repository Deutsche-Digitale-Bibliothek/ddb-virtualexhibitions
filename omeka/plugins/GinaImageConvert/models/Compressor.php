<?php
/**
 * Image Compressor
 * @copyright Copyright 2020 Viktor Grandgeorg
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 */

/**
 * Compressor.
 *
 */
class Compressor
{
    public $filename = '';
    public $basePath = '';
    // public $stateFile = '';
    public $logFile = '';
    public $options = null;
    public $dirs = array();
    public $log = array();
    public $maxQuality = 90;

    public function __construct($filename, $filesdir, $options)
    {
        $this->filename = $filename;
        $this->setBasePath($filesdir);
        $this->setOptions($options);
        $this->setDirs();
    }

    public function setBasePath($filesdir)
    {
        $this->basePath = $filesdir;
    }

    public function setOptions($options)
    {
        $this->options = $options;
    }

    public function setDirs()
    {
        $basePath = $this->basePath . DIRECTORY_SEPARATOR;
        foreach ($this->options as $type => $option) {
            $this->dirs[$type] = $basePath . $type;
        }
        $this->dirs['original_compressed'] = $basePath . 'original_compressed';

        // $this->dirs = array(
        //     'original' => $basePath . 'original',
        //     'original_compressed' => $basePath . 'original_compressed',
        //     'fullsize' => $basePath . 'fullsize',
        //     'middsize' => $basePath . 'middsize',
        //     'thumbnails' => $basePath . 'thumbnails',
        //     'square_thumbnails' => $basePath . 'square_thumbnails'
        // );
    }

    public function main()
    {
        $this->checkOriginalCompressedDir();
        foreach ($this->options as $sizeKey => $sizeOptions) {
            if ($sizeKey === 'original') {
                $this->compress($sizeKey, 'original_compressed', $sizeKey);
            } else {
                $this->compressSized($sizeKey);
            }
        }
    }

    public function checkOriginalCompressedDir()
    {
        if (!is_dir($this->dirs['original_compressed'])) {
            mkdir($this->dirs['original_compressed'], 0755);
        }
    }

    public function getRecompressCommand($in, $out, $type)
    {
        // Do not use --strip option, as it will remove ICC profiles'
        return 'jpeg-recompress'
        . ' --target '   . $this->options[$type]['recompress_target']
        . ' --min '      . $this->options[$type]['recompress_min']
        . ' --max '      . $this->options[$type]['recompress_max']
        . ' --loops '    . $this->options[$type]['recompress_loops']
        . ' --method '   . $this->options[$type]['recompress_method']
        . ' --accurate '
        . $in
        . ' '
        . $out
        . ' 2>&1';
    }

    public function compress($intype, $outtype, $type)
    {
        $ext = array('jpg', 'jpeg');
        if (is_file($this->dirs[$intype] . DIRECTORY_SEPARATOR . $this->filename) &&
            in_array(
                strtolower(
                    pathinfo($this->filename, PATHINFO_EXTENSION)
                ),
                $ext
            )
        ) {
            $infile = $this->dirs[$intype]
                . DIRECTORY_SEPARATOR
                . $this->filename;

            $outfile = $this->dirs[$outtype]
                . DIRECTORY_SEPARATOR
                . $this->filename;

            $recompress = $this->getRecompressCommand($infile, $outfile, $type);
            $output = array();
            $retval = false;
            exec($recompress, $output, $retval);

            $this->log[$type] = array(
                'time' => date('Y.m.d. H:i:s'),
                'error' => $retval,
                'compress' => $output
            );
        }
    }

    public function compressSized($type)
    {
        $ext = array('jpg', 'jpeg', 'png');
        $fileExtension = strtolower(pathinfo($this->filename, PATHINFO_EXTENSION));
        $filename = pathinfo($this->filename, PATHINFO_FILENAME);

        if (is_file($this->dirs['original'] . DIRECTORY_SEPARATOR . $this->filename) &&
            in_array($fileExtension, $ext)) {

            $this->resizeImage($type);

            $file = $this->dirs[$type]
                . DIRECTORY_SEPARATOR
                . $filename . '.jpg';

            $recompress = $this->getRecompressCommand($file, $file, $type);
            $output = array();
            $retval = false;
            exec($recompress, $output, $retval);

            $this->log[$type] = array(
                'time' => date('Y.m.d. H:i:s'),
                'error' => $retval,
                'compress' => $output
            );
        }
    }

    public function resizeImage($type)
    {
        if(extension_loaded('imagick')) {
            $img = new Imagick($this->dirs['original'] . DIRECTORY_SEPARATOR . $this->filename);

            // remove Exif etc. but keep ICC
            $profiles = $img->getImageProfiles('icc', true);
            $img->stripImage();
            if (isset($profiles) && !empty($profiles) && isset($profiles['icc'])) {
                $img->profileImage('icc', $profiles['icc']);
            }

            $quality = $img->getImageCompressionQuality();
            if ($quality === 0 ||
                $quality > (int) $this->options[$type]['resize_max_quality']
            ) {
                $quality = (int) $this->options[$type]['resize_max_quality'];
            }

            if ($this->options[$type]['resize_square'] === '1') {
                $img->cropThumbnailImage(
                    $this->options[$type]['resize_width'],
                    $this->options[$type]['resize_width']);
            } else {
                $img->resizeImage(
                    $this->options[$type]['resize_width'],
                    $this->options[$type]['resize_height'],
                    Imagick::FILTER_LANCZOS, 1, true);
            }

            $img->setImageCompression(Imagick::COMPRESSION_JPEG);
            $img->setImageCompressionQuality($quality);

            $img->writeImage(
                $this->dirs[$type] . DIRECTORY_SEPARATOR .
                pathinfo($this->filename, PATHINFO_FILENAME) . '.jpg'
            );
            $img->clear();
            $img->destroy();
        }
    }

    public function getLog()
    {
        return array(
            'params' => $this->options,
            'files' => $this->log
        );
    }
}