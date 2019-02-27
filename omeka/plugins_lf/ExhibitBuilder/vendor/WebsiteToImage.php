<?php
/*  http://www.phpgangsta.de/screenshots-von-webseiten-erstellen-mit-php
 *
 *  Usage:
 *  require_once 'WebsiteToImage.php';
 *  
 *  $websiteToImage = new WebsiteToImage();
 *  $websiteToImage->setProgramPath('/path/to/wkhtmltoimage-i386')
 *                 ->setOutputFile('path/to/foo.jpg')
 *                 ->setQuality(70)
 *                 ->setUrl('http://www.google.de')
 *                 ->start();
 *
 *  $websiteToImage->setResizedOutputFile('/path/to/bar.jpg')
 *                 ->setResize('150x150')
 *                 ->start();
 *                 ->convertRisize();
 * 
 * some help for the command line testing:
 * wkhtmltoimage-amd64 --crop-y 244 --crop-x 38 --crop-h 952 --crop-w 946 http://127.0.0.1/exhibits/show/ddb-tempalte/l1 test.jpg
 *
 *
 * wkhtmltoimage-i386 --username omeka --password ddb2013 --crop-y 244 --crop-x 38 --crop-h 952 --crop-w 946 http://testplayomeka. *culture-to-go.de/exhibits/show/seitentemplates/test-ddb ./test.jpg *
 *
 *
 *
 * ./wkhtmltoimage-amd64-0.10.0 --crop-y 244 --crop-x 38 --crop-h 952 --crop-w 946 http://127.0.0.1/exhibits/show/ddb-tempalte/l1  *./test.jpg
 *  
 */

class WebsiteToImage
{
    const FORMAT_JPG  = 'jpg';
    const FORMAT_JPEG = 'jpeg';
    const FORMAT_PNG  = 'png';
    const FORMAT_TIF  = 'tif';
    const FORMAT_TIFF = 'tiff';

    protected $_options;
    protected $_programPath;
    protected $_outputFile;
    protected $_resizedOutputFile;
    protected $_url;
    protected $_format = self::FORMAT_JPG;
    protected $_quality = 90;
    protected $_resize = '90x90';

    public function start()
    {
        $programPath = escapeshellcmd($this->_programPath);
        $options = escapeshellcmd($this->_options);
        $outputFile  = escapeshellarg($this->_outputFile);
        $url         = escapeshellarg($this->_url);
        $format      = escapeshellarg($this->_format);
        $quality     = escapeshellarg($this->_quality);

        $command = "$programPath  $options --format $format --quality $quality $url $outputFile";
        exec($command);
    }

    public function setOptions($options)
    {
        $this->_options = $options;
        return $this;
    }

    public function getOptions()
    {
        return $this->_options;
    }

    public function setOutputFile($outputFile)
    {
        clearstatcache();
        if (!is_writable(dirname($outputFile))) {
            throw new Exception('output file not writable - filepath is: ' . $outputFile);
        }
        
        $this->_outputFile = $outputFile;
        return $this;
    }

    public function getOutputFile()
    {
        return $this->_outputFile;
    }

    public function setResizedOutputFile($outputFile)
    {
        clearstatcache();
        if (!is_writable(dirname($outputFile))) {
            throw new Exception('output file not writable');
        }
        
        $this->_resizedOutputFile = $outputFile;
        return $this;
    }

    public function getResizedOutputFile()
    {
        return $this->_resizedOutputFile;
    }

    public function setProgramPath($programPath)
    {
        $this->_programPath = $programPath;
        return $this;
    }

    public function getProgramPath()
    {
        return $this->_programPath;
    }

    public function setFormat($format)
    {
        $this->_format = $format;
        return $this;
    }

    public function getFormat()
    {
        return $this->_format;
    }

    public function setQuality($quality)
    {
        $this->_quality = (int)$quality;
        return $this;
    }

    public function getQuality()
    {
        return $this->_quality;
    }

    public function setUrl($url)
    {
        $this->_url = $url;
        return $this;
    }

    public function getUrl()
    {
        return $this->_url;
    }

    public function setResize($resize)
    {
        $this->_resize = $resize;
        return $this;
    }

    public function getResize()
    {
        return $this->_resize;
    }

    public function convertRisize()
    {
        $convert = escapeshellcmd('convert');
        $inputFile  = escapeshellarg($this->_outputFile);
        $resizedOutputFile = escapeshellarg($this->_resizedOutputFile);
        $size = escapeshellarg($this->_resize);

        $command = "$convert $inputFile -quality 90 -resize $size $resizedOutputFile";

        exec($command);
        unlink($this->_outputFile);
    }

}