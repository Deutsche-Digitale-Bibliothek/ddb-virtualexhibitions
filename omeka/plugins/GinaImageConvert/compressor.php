<?php

class Compressor
{
    public $dir = '';
    public $basePath = '';
    public $stateFile = '';
    public $logFile = '';
    public $options = null;
    public $dirs = array();
    public $log = array();
    // public $maxQuality = 90;
    public $webp;

    public function __construct($dir = '')
    {
        $this->dir = $dir;
        $this->setBasePath();
        $this->setStateFile();
        $this->setLogFile();
        $this->setOptions();
        $this->setDirs();
        $this->setWebp();
    }

    public function setBasePath()
    {
        if (is_dir($this->dir)) {
            $this->basePath = $this->dir;
        }
    }

    public function setStateFile()
    {
        $this->stateFile = realpath(
            $this->basePath .
            DIRECTORY_SEPARATOR .
            'compress_state.txt'
        );
    }

    public function setLogFile()
    {
        $this->logFile = realpath(
            $this->basePath .
            DIRECTORY_SEPARATOR .
            'compress.log'
        );
    }

    public function setOptions()
    {
        $this->options = json_decode(
            file_get_contents($this->logFile),
            true
        );
    }

    public function setDirs()
    {
        $basePath = $this->basePath . DIRECTORY_SEPARATOR;
        foreach ($this->options['params'] as $type => $option) {
            $this->dirs[$type] = $basePath . $type;
        }
        $this->dirs['original_compressed'] = $basePath . 'original_compressed';

        // $this->dirs = array(
        //     'original' => $basePath . 'original',
        //     'original_compressed' => $basePath . 'original_compressed',
        //     'fullsize' => $basePath . 'fullsize',
        //     'middsize' => $basePath . 'middsize',
        //     'square_thumbnails' => $basePath . 'square_thumbnails',
        //     'thumbnails' => $basePath . 'thumbnails',
        // );
    }

    public function setWebp()
    {
        if (!isset($this->webp) || empty($this->webp)) {
            require_once __DIR__ . '/models/Webp.php';
            $this->webp = new Webp($this->basePath);
        }
    }

    public function main()
    {
        $this->checkOriginalCompressedDir();
        foreach ($this->options['params'] as $type => $option) {
            if ($type === 'original') {
                $this->compress($type, 'original_compressed', $type, true);
            } else {
                $this->compressSized($type, true);
            }
        }
        file_put_contents($this->stateFile, 'off');
        $this->writeLogfile();
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
        . ' --target '  . $this->options['params'][$type]['recompress_target']
        . ' --min '     . $this->options['params'][$type]['recompress_min']
        . ' --max '     . $this->options['params'][$type]['recompress_max']
        . ' --loops '   . $this->options['params'][$type]['recompress_loops']
        . ' --method '  . $this->options['params'][$type]['recompress_method']
        . ' --accurate '
        . $in
        . ' '
        . $out
        . ' 2>&1';
    }

    public function compress($intype, $outtype, $type, $log)
    {
        $ext = array('jpg', 'jpeg');
        $iterator = new DirectoryIterator($this->dirs[$intype]);
        foreach ($iterator as $entry) {
            if ($entry->isFile() &&
                in_array(
                    strtolower(
                        pathinfo($entry->getFilename(), PATHINFO_EXTENSION)
                    ),
                    $ext
                )
            ) {
                $outfile = $this->dirs[$outtype]
                    . DIRECTORY_SEPARATOR
                    . $entry->getFilename();

                $recompress = $this->getRecompressCommand($entry->getPathname(), $outfile, $type);
                $output = array();
                $retval = false;
                exec($recompress, $output, $retval);

                if ($log) {
                    $this->log[$entry->getFilename()][$outtype] = array(
                        'file' => $entry->getFilename(),
                        'time' => date('Y.m.d H:i:s'),
                        'error' => $retval,
                        'compress' => $output

                    );
                }
            }
        }
    }

    public function compressSized($type, $log)
    {
        $ext = array('jpg', 'jpeg');
        // $extadd = array('png', 'gif');
        $extadd = array();
        $iterator = new DirectoryIterator($this->dirs['original']);
        foreach ($iterator as $entry) {

            $fileName = $entry->getFilename();
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            if (
                ($entry->isFile() && in_array($fileExtension, $ext)) ||
                ($entry->isFile() && in_array($fileExtension, $extadd))
            ) {

                $this->resizeImage(
                    $type,
                    $fileName
                );
                $file = $this->dirs[$type]
                    . DIRECTORY_SEPARATOR
                    . pathinfo($fileName, PATHINFO_FILENAME)
                    . '.jpg';

                $recompress = $this->getRecompressCommand($file, $file, $type);
                $output = array();
                $retval = false;
                exec($recompress, $output, $retval);

                if ($log) {
                    $this->log[$fileName][$type] = array(
                        'time' => date('Y.m.d H:i:s'),
                        'error' => $retval,
                        'compress' => $output

                    );
                }

            }

            // PNG to WEBP
            if ($entry->isFile() && $fileExtension === 'png') {
                $options = array(
                    'resize_width'  => $this->options['params'][$type]['resize_width'],
                    'resize_height' => $this->options['params'][$type]['resize_height'],
                    'resize_square' => $this->options['params'][$type]['resize_square'],
                    'webp_quality'  => $this->options['params'][$type]['webp_quality'],
                    'type'          => $type
                );
                $sourcePath = $this->dirs['original'] . DIRECTORY_SEPARATOR . $fileName;
                $destPath = $this->dirs[$type] . DIRECTORY_SEPARATOR . $type . '_xxx_' . $fileName;
                $this->webp->run($sourcePath, $destPath, $options);
            }

        }
        // $this->writeLogfile();
    }

    public function resizeImage($type, $file)
    {
        if(extension_loaded('imagick')) {
            $img = new Imagick($this->dirs['original'] . DIRECTORY_SEPARATOR . $file);

            // remove Exif etc. but keep ICC
            $profiles = $img->getImageProfiles('icc', true);
            $img->stripImage();
            if (isset($profiles) && !empty($profiles) && isset($profiles['icc'])) {
                $img->profileImage('icc', $profiles['icc']);
            }

            $quality = $img->getImageCompressionQuality();
            if ($quality === 0 ||
                $quality > (int) $this->options['params'][$type]['resize_max_quality']
            ) {
                $quality = (int) $this->options['params'][$type]['resize_max_quality'];
            }

            if ($this->options['params'][$type]['resize_square'] === '1') {
                $img->cropThumbnailImage(
                    $this->options['params'][$type]['resize_width'],
                    $this->options['params'][$type]['resize_width']);
            } else {
                $img->resizeImage(
                    $this->options['params'][$type]['resize_width'],
                    $this->options['params'][$type]['resize_height'],
                    Imagick::FILTER_LANCZOS, 1, true);
            }

            $img->setImageCompression(Imagick::COMPRESSION_JPEG);
            $img->setImageCompressionQuality($quality);

            $img->writeImage(
                $this->dirs[$type] . DIRECTORY_SEPARATOR .
                pathinfo($file, PATHINFO_FILENAME) . '.jpg'
            );
            $img->clear();
            $img->destroy();
        }
    }

    public function writeLogfile()
    {
        $log = array(
            'start' => $this->options['start'],
            'end' => date('Y.m.d H:i:s'),
            'params' => $this->options['params'],
            'files' => $this->log
        );
        file_put_contents($this->logFile, json_encode($log));
    }
}

$shortopts = "d:";
$longopts  = array("dir:");
$options = getopt($shortopts, $longopts);
$dir = null;
if (isset($options['dir'])) {
    $dir = $options['dir'];
} elseif (isset($options['d'])) {
    $dir = $options['d'];
}
if (!isset($dir)) {
    exit(1);
} else {
    $compressor = new Compressor($dir);
    $compressor->main();
}
?>