<?php

namespace Palettes\APIBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Palettes\CoreBundle\Model\PaletteQuery;

/**
 * API dla listy palet.
 *
 * @author Adam Wójs <adam@wojs.pl>
 */
class PaletteController extends FOSRestController {

    /**
     * Zwraca listę palet.
     * 
     * @return array
     */
    public function getPalettesAction() {
        $palettes = PaletteQuery::create()
            ->find()
            ->toArray();

        return $this->handleView($this->view($palettes, 200));
    }

    /**
     * Zwraca informacje o palecie
     * 
     * @param int $id Identyfikator palety
     * @return Palette
     */
    public function getPaletteAction($id) {
        $palette = PaletteQuery::create()
            ->joinColor()
            ->findPk($id);
        
        if(!$palette) {
            throw $this->createNotFoundException("Palette $id not exists!");
        }
                
        return $this->handleView($this->view($palette->toArray(), 200));
    }
}
