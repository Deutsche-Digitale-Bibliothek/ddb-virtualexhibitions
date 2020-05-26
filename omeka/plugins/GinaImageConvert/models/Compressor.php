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
        $this->dirs = array(
            'original' => $basePath . 'original',
            'original_compressed' => $basePath . 'original_compressed',
            'fullsize' => $basePath . 'fullsize',
            'middsize' => $basePath . 'middsize',
            'square_thumbnails' => $basePath . 'square_thumbnails',
            'thumbnails' => $basePath . 'thumbnails',
        );
    }

    public function main()
    {
        $this->checkOriginalCompressedDir();
        $this->compress('original', 'original_compressed', 'original');
        $this->compressSized('fullsize', 1920, 1080);
        $this->compressSized('middsize', 960, 960);
        $this->compressSized('thumbnails', 360, 360);
        $this->compress('square_thumbnails', 'square_thumbnails', 'square_thumbnails');
    }

    public function checkOriginalCompressedDir()
    {
        if (!is_dir($this->dirs['original_compressed'])) {
            mkdir($this->dirs['original_compressed'], 0755);
        }
    }

    public function getRecompressCommand($in, $out, $type)
    {
        return 'jpeg-recompress'
        . ' --target '  . $this->options['compress_' . $type . '_target']
        . ' --min '     . $this->options['compress_' . $type . '_min']
        . ' --max '     . $this->options['compress_' . $type . '_max']
        . ' --loops '   . $this->options['compress_' . $type . '_loops']
        . ' --accurate --strip '
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

    public function compressSized($type, $width, $height)
    {
        $ext = array('jpg', 'jpeg');

        if (is_file($this->dirs['original'] . DIRECTORY_SEPARATOR . $this->filename) &&
            in_array(
                strtolower(
                    pathinfo($this->filename, PATHINFO_EXTENSION)
                ),
                $ext
            )
        ) {

            $this->resizeImage(
                $this->dirs['original'],
                $this->dirs[$type],
                $this->filename,
                $width,
                $height
            );
            $file = $this->dirs[$type]
                . DIRECTORY_SEPARATOR
                . $this->filename;

            $recompress = $recompress = $this->getRecompressCommand($file, $file, $type);
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

    public function resizeImage($srcDir, $outDir, $file, $width, $height)
    {
        if(extension_loaded('imagick')) {
            $img = new Imagick($srcDir . DIRECTORY_SEPARATOR . $file);

            // removeExif
            $profiles = $img->getImageProfiles('icc', true);
            $img->stripImage();
            if (isset($profiles) && !empty($profiles) && isset($profiles['icc'])) {
                $img->profileImage('icc', $profiles['icc']);
            }

            // make max Quality selectable?
            $quality = $img->getImageCompressionQuality();
            // echo $file . ' - ' . $quality . "\n";
            if ($quality > 75) {
                $quality = 75;
            }

            // imagick::FILTER_LANCZOS, slow but good ...
            $img->resizeImage($width, $height, Imagick::FILTER_LANCZOS, 1, true);

            $img->setImageCompression(Imagick::COMPRESSION_JPEG);
            $img->setImageCompressionQuality($quality);

            $img->writeImage($outDir . DIRECTORY_SEPARATOR . $file);
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