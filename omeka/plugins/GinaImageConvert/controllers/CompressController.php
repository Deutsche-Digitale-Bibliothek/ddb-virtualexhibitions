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
        $params = $this->getCompressParams();
        $this->view->params = $params;
        $compressall = $this->_request->getParam('compressall_submit', null);
        $stateFile = BASE_DIR . '/files/compress_state.txt';
        $logFile = BASE_DIR . '/files/compress.log';
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
                    'params' => $params
                );
                file_put_contents($logFile, json_encode($logContent));
                $compressor = __DIR__ . '/../compressor.php';
                exec('php ' . $compressor . ' --slug ' . $slug . ' >/dev/null 2>/dev/null &');

                $this->_helper->flashMessenger('Komprimierungsprozess erfolgreich gestartet...', 'success');
                $this->_helper->redirector('index', 'compress', 'gina-image-convert');
            } else {
                $this->_helper->flashMessenger('Serverfehler: Datei compress_state.txt konnte nicht geschrieben werden.', 'error');
                $this->_helper->redirector('index', 'compress', 'gina-image-convert');
            }
        }
    }

    public function showlogAction()
    {
        $stateFile = BASE_DIR . '/files/compress_state.txt';
        $logFile = BASE_DIR . '/files/compress.log';
        $checkState = 'off';
        if (is_file($stateFile) && is_readable($stateFile)) {
            $checkState = file_get_contents($stateFile);
        }
        if ($checkState === 'on') {
            $this->_helper->flashMessenger('Logdatei kann nicht ausgewertet werden, da der Komprimierungsprozess im Gange ist.', 'error');
            $this->_helper->redirector('index', 'compress', 'gina-image-convert');
            return;
        }
        $db = $this->_helper->db->getDb();
        $this->view->dbFiles = $db->getTable('File')->findAll();
        $this->view->logfile = json_decode(file_get_contents($logFile), true);

    }

    public function fileAction()
    {
        $id = $this->_request->getParam('id', null);
        if (!isset($id) || empty($id)) {
            $this->_helper->flashMessenger('Sie müssen eine Bilddatei zur Komprimierung auswählen!', 'error');
            $this->_helper->redirector(null, null, 'items');
            return;
        }
        $this->view->id = $id;

        $db = $this->_helper->db->getDb();
        $dbFile = $db->getTable('File')->find($id);
        if (!isset($dbFile) || empty($dbFile)) {
            $this->_helper->flashMessenger('Ausgewählte Bilddatei konnte nicht gefunden werden!', 'error');
            $this->_helper->redirector(null, null, 'items');
            return;
        }

        if ($dbFile->mime_type !== 'image/jpeg') {
            $this->_helper->flashMessenger('Derzeit können nur Bilddateien vom Typ JPEG rekomprimiert werden! Die Datei hat den Typ ' . $dbFile->mime_type, 'error');
            $this->_helper->redirector(null, null, 'items');
            return;
        }
        $this->view->dbFile = $dbFile;

        $params = $this->getFileCompressParams();
        $this->view->params = $params;
        $docompress = $this->_request->getParam('compress_submit', null);

        if ($docompress) {
            // var_dump($params);
            require __DIR__ . '/../models/Compressor.php';
            $compressor = new Compressor($dbFile->filename, FILES_DIR, $params);
            $compressor->main();
            $this->view->log = $compressor->getLog();
        }

    }

    protected function getCompressParams()
    {
        $params = $this->_request->getParams();
        unset(
            $params['admin'],
            $params['module'],
            $params['controller'],
            $params['action'],
            $params['compressall_submit']
        );
        if (!isset($params['compressall_target']) || empty($params['compressall_target'])) {
            $params['compressall_target'] = '0.9999';
        }
        if (!isset($params['compressall_min']) || empty($params['compressall_min'])) {
            $params['compressall_min'] = '40';
        }
        if (!isset($params['compressall_max']) || empty($params['compressall_max'])) {
            $params['compressall_max'] = '95';
        }
        if (!isset($params['compressall_loops']) || empty($params['compressall_loops'])) {
            $params['compressall_loops'] = '6';
        }
        // if (!isset($params['compressall_method']) || empty($params['compressall_method'])) {
        //     $params['compressall_method'] = 'ssim';
        // }
        return $params;
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

        if (!isset($params['compress_original_target']) || empty($params['compress_original_target'])) {
            $params['compress_original_target'] = '0.9999';
        }
        if (!isset($params['compress_original_min']) || empty($params['compress_original_min'])) {
            $params['compress_original_min'] = '40';
        }
        if (!isset($params['compress_original_max']) || empty($params['compress_original_max'])) {
            $params['compress_original_max'] = '95';
        }
        if (!isset($params['compress_original_loops']) || empty($params['compress_original_loops'])) {
            $params['compress_original_loops'] = '6';
        }
        if (!isset($params['compress_original_method']) || empty($params['compress_original_method'])) {
            $params['compress_original_method'] = 'ssim';
        }

        if (!isset($params['compress_fullsize_target']) || empty($params['compress_fullsize_target'])) {
            $params['compress_fullsize_target'] = '0.9999';
        }
        if (!isset($params['compress_fullsize_min']) || empty($params['compress_fullsize_min'])) {
            $params['compress_fullsize_min'] = '40';
        }
        if (!isset($params['compress_fullsize_max']) || empty($params['compress_fullsize_max'])) {
            $params['compress_fullsize_max'] = '95';
        }
        if (!isset($params['compress_fullsize_loops']) || empty($params['compress_fullsize_loops'])) {
            $params['compress_fullsize_loops'] = '6';
        }
        if (!isset($params['compress_fullsize_method']) || empty($params['compress_fullsize_method'])) {
            $params['compress_fullsize_method'] = 'ssim';
        }

        if (!isset($params['compress_middsize_target']) || empty($params['compress_middsize_target'])) {
            $params['compress_middsize_target'] = '0.9999';
        }
        if (!isset($params['compress_middsize_min']) || empty($params['compress_middsize_min'])) {
            $params['compress_middsize_min'] = '40';
        }
        if (!isset($params['compress_middsize_max']) || empty($params['compress_middsize_max'])) {
            $params['compress_middsize_max'] = '95';
        }
        if (!isset($params['compress_middsize_loops']) || empty($params['compress_middsize_loops'])) {
            $params['compress_middsize_loops'] = '6';
        }
        if (!isset($params['compress_middsize_method']) || empty($params['compress_middsize_method'])) {
            $params['compress_middsize_method'] = 'ssim';
        }

        if (!isset($params['compress_thumbnails_target']) || empty($params['compress_thumbnails_target'])) {
            $params['compress_thumbnails_target'] = '0.9999';
        }
        if (!isset($params['compress_thumbnails_min']) || empty($params['compress_thumbnails_min'])) {
            $params['compress_thumbnails_min'] = '40';
        }
        if (!isset($params['compress_thumbnails_max']) || empty($params['compress_thumbnails_max'])) {
            $params['compress_thumbnails_max'] = '95';
        }
        if (!isset($params['compress_thumbnails_loops']) || empty($params['compress_thumbnails_loops'])) {
            $params['compress_thumbnails_loops'] = '6';
        }
        if (!isset($params['compress_thumbnails_method']) || empty($params['compress_thumbnails_method'])) {
            $params['compress_thumbnails_method'] = 'ssim';
        }

        if (!isset($params['compress_square_thumbnails_target']) || empty($params['compress_square_thumbnails_target'])) {
            $params['compress_square_thumbnails_target'] = '0.9999';
        }
        if (!isset($params['compress_square_thumbnails_min']) || empty($params['compress_square_thumbnails_min'])) {
            $params['compress_square_thumbnails_min'] = '40';
        }
        if (!isset($params['compress_square_thumbnails_max']) || empty($params['compress_square_thumbnails_max'])) {
            $params['compress_square_thumbnails_max'] = '95';
        }
        if (!isset($params['compress_square_thumbnails_loops']) || empty($params['compress_square_thumbnails_loops'])) {
            $params['compress_square_thumbnails_loops'] = '6';
        }
        if (!isset($params['compress_square_thumbnails_method']) || empty($params['compress_square_thumbnails_method'])) {
            $params['compress_square_thumbnails_method'] = 'ssim';
        }

        return $params;
    }

    protected function getSlug()
    {
        return substr(BASE_DIR, (strrpos(BASE_DIR, '/') + 1));
    }

}
