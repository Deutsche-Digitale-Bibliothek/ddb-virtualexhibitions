<?php
/**
 * ExhibitColorSchemeDesigner
 * @copyright Copyright 2020 Viktor Grandgeorg
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 */

/**
 * index controller.
 *
 */
class ExhibitColorSchemeDesigner_IndexController extends Omeka_Controller_AbstractActionController
{

    public function indexAction()
    {
        $params = $this->_request->getParams();

        $dbExhibit = $this->_helper->db->getTable('Exhibit')->findAll();
        $colorpalette = 'a';
        foreach ($dbExhibit as $ex) {
            $colorpalette = $ex->colorpalette;
        }
        $dbExhibitColorPalette = $this->_helper->db->getTable('ExhibitColorPalette');
        $colors = $dbExhibitColorPalette->findBy(array('palette' => $colorpalette));
        $this->view->colors = $colors;
        $this->view->colorpalette = $colorpalette;

        // save edit
        if (isset($params['exhibit-color-scheme-designer_submit']) &&
            !empty($params['exhibit-color-scheme-designer_submit']) &&
            isset($params['exhibit-color-scheme-designer_colorpalette']) &&
            !empty($params['exhibit-color-scheme-designer_colorpalette']) &&
            $params['exhibit-color-scheme-designer_colorpalette'] == $colorpalette
        ) {
            // delete old entries
            var_dump($params);
            foreach ($colors as $color) {
                $color->delete();
            }

            // save
            foreach ($params['palette'] as $newColorKey => $newColor) {
                $newColor['color'] = preg_replace('/[^a-z0-9_\-]/', '', strtolower($newColor['color']));
                if (!empty($newColor['color'])) {
                    $dbNewColor = new ExhibitColorPalette();
                    $dbNewColor->palette = $params['exhibit-color-scheme-designer_colorpalette'];
                    $dbNewColor->color = $newColor['color'];
                    $dbNewColor->hex = $newColor['hex'];
                    $dbNewColor->type = $newColor['type'];
                    if ($newColorKey == $params['palette_menu']) {
                        $dbNewColor->menu = 1;
                    } else {
                        $dbNewColor->menu = 0;
                    }
                    $dbNewColor->save();
                }
            }

            $this->_helper->flashMessenger(__('Farpalette erfolgreich gespeichert.'), 'success');
            $this->_helper->redirector->gotoUrl('exhibit-color-scheme-designer');
        }
    }
}
