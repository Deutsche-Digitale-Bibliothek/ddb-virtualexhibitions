<?php
/**
 * Image Compress
 *
 * @copyright Copyright 2020 Grandgeorg Websolutions
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 */

/**
 * The Gina Image Convert Compress controller class.
 *
 * @package GinaImageConvert
 */
class GinaImageConvert_CompressController extends Omeka_Controller_AbstractActionController
{
    public function init() {}

    public function indexAction()
    {
        $params = $this->getFileCompressParams();
        $this->view->params = $params;
        $compressall = $this->_request->getParam('compressall_submit', null);
        $stateFile = FILES_DIR . '/compress_state.txt';
        $logFile = FILES_DIR . '/compress.log';
        if (is_file($logFile)) {
            $this->view->logfileExists = true;
        } else {
            $this->view->logfileExists = false;
        }
        $slug = $this->getSlug();
        $checkState = 'off';
        if (is_file($stateFile) && is_readable($stateFile)) {
            $checkState = file_get_contents($stateFile);
        }
        $this->view->checkState = $checkState;
        if ($compressall && ($checkState === false || $checkState === 'off')) {
            if (false !== file_put_contents($stateFile, 'on')) {
                // ... do the compress ...
                $logContent = array(
                    'start' => date('Y.m.d H:i:s'),
                    'params' => $params['compress']
                );
                file_put_contents($logFile, json_encode($logContent));
                $compressor = __DIR__ . '/../compressor.php';
                exec('php ' . $compressor . ' --dir ' . FILES_DIR . ' >/dev/null 2>/dev/null &');

                $this->_helper->flashMessenger(
                    'Komprimierungsprozess erfolgreich gestartet...', 'success');
                $this->_helper->redirector('index', 'compress', 'gina-image-convert');
            } else {
                $this->_helper->flashMessenger(
                    'Serverfehler: Datei compress_state.txt konnte nicht geschrieben werden.',
                    'error');
                $this->_helper->redirector('index', 'compress', 'gina-image-convert');
            }
        }
    }

    public function showlogAction()
    {
        $stateFile = FILES_DIR . '/compress_state.txt';
        $logFile = FILES_DIR . '/compress.log';
        $checkState = 'off';
        if (is_file($stateFile) && is_readable($stateFile)) {
            $checkState = file_get_contents($stateFile);
        }
        if ($checkState === 'on') {
            $this->_helper->flashMessenger(
                'Logdatei kann nicht ausgewertet werden, da der ' .
                'Komprimierungsprozess im Gange ist.', 'error');
            $this->_helper->redirector('index', 'compress', 'gina-image-convert');
            return;
        }
        $db = $this->_helper->db->getDb();
        $this->view->showOptions = array(
            'recompress_target' => 'Ziel-Qualität',
            'recompress_min' => 'Minimum JPEG Qualität',
            'recompress_max' => 'Maximum JPEG Qualität',
            'recompress_loops' => 'Anzahl der Versuchsläufe',
            'recompress_method' => 'Methode'
        );
        $this->view->sizeNames = array(
            'original_compressed' => 'Original komprimiert (original compressed)',
            'fullsize' => 'Volle Größe (fullsize)',
            'middsize' => 'Mittlere Größe (middsize)',
            'thumbnails' => 'Vorschau (thumbnails)',
            'square_thumbnails' => 'Quadratische Vorschau (square thumbnails)'
        );
        $this->view->dbFiles = $db->getTable('File')->findAll();
        $this->view->logfile = json_decode(file_get_contents($logFile), true);

    }

    public function fileAction()
    {
        $id = $this->_request->getParam('id', null);
        if (!isset($id) || empty($id)) {
            $this->_helper->flashMessenger(
                'Sie müssen eine Bilddatei zur Komprimierung auswählen!',
                'error');
            $this->_helper->redirector(null, null, 'items');
            return;
        }
        $this->view->id = $id;

        $db = $this->_helper->db->getDb();
        $dbFile = $db->getTable('File')->find($id);
        if (!isset($dbFile) || empty($dbFile)) {
            $this->_helper->flashMessenger(
                'Ausgewählte Bilddatei konnte nicht gefunden werden!',
                'error');
            $this->_helper->redirector(null, null, 'items');
            return;
        }

        $mimes = array('image/jpeg', 'image/png');
        if (!in_array($dbFile->mime_type, $mimes)) {
            $this->_helper->flashMessenger(
                'Derzeit können nur Bilddateien vom Typ JPEG und PNG ' .
                'rekomprimiert werden! Die Datei hat den Typ ' .
                $dbFile->mime_type, 'error');
            $this->_helper->redirector(null, null, 'items');
            return;
        }
        $this->view->dbFile = $dbFile;

        $params = $this->getFileCompressParams();
        $this->view->params = $params;
        $docompress = $this->_request->getParam('compress_submit', null);

        if ($docompress) {
            $this->view->fileSizesOld = $this->getFileSizes($dbFile);
            require __DIR__ . '/../models/Compressor.php';
            $compressor = new Compressor($dbFile->filename, FILES_DIR, $params['compress']);
            $compressor->main();
            $this->view->log = $compressor->getLog();
        }
        // Make sure to get filsizes after compression
        $this->view->fileSizes = $this->getFileSizes($dbFile);
    }

    protected function getFileSizes($dbFile)
    {
        $types = array(
            'original',
            'original_compressed',
            'fullsize',
            'middsize',
            'thumbnails',
            'square_thumbnails'
        );
        $sizes = array();
        foreach ($types as $type) {
            if ($type === 'original' || $type === 'original_compressed') {
                $file = FILES_DIR . DIRECTORY_SEPARATOR .
                        $type . DIRECTORY_SEPARATOR .
                        $dbFile->filename;
            } else {
                $file = FILES_DIR . DIRECTORY_SEPARATOR .
                        $type . DIRECTORY_SEPARATOR .
                        pathinfo($dbFile->filename, PATHINFO_FILENAME) .
                        '.jpg';
            }
            if (is_file($file)) {
                $sizes[$type] = round((filesize($file) / 1024), 2);
            }
        }
        return $sizes;
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
        return require dirname(__FILE__) . '/../default_config.php';
    }

    protected function getFileCompressParams()
    {
        $params = $this->_request->getParams();
        unset(
            $params['admin'],
            $params['module'],
            $params['controller'],
            $params['action'],
            $params['compress_submit']
        );

        $options = $this->mergeOptions(
            $this->getDefaultConfig(),
            unserialize(get_option('gina_image_convert'))
        );

        $compressParams = array('compress' => array());
        foreach ($options as $sizeKey => $sizeOptions) {
            foreach ($sizeOptions as $key => $option) {
                if (isset($params['compress'][$sizeKey][$key]) && !empty($params['compress'][$sizeKey][$key])) {
                    $compressParams['compress'][$sizeKey][$key] = $params['compress'][$sizeKey][$key];
                } else {
                    $compressParams['compress'][$sizeKey][$key] = $option;
                }
            }
        }

        return $compressParams;
    }

    protected function getSlug()
    {
        return substr(BASE_DIR, (strrpos(BASE_DIR, '/') + 1));
    }

}
