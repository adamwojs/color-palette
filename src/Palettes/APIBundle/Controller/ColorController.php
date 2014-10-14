<?php

namespace Palettes\APIBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Palettes\CoreBundle\Model\PaletteQuery;
use Palettes\CoreBundle\Model\ColorQuery;

/**
 * API dla listy kolorów w paletach.
 *
 * @author Adam Wójs <adam@wojs.pl>
 */
class ColorController extends FOSRestController {
    
    /**
     * Zwraca listę kolorów w palecie.
     * 
     * @param int $paletteId Identyfikator palety
     * @return array
     */
    public function getColorsAction($paletteId) {
        $palette = PaletteQuery::create()
            ->joinColor()
            ->findPk($paletteId);
        
        if(!$palette) {
            throw $this->createNotFoundException("Palette $paletteId not exists!");
        }

        $colors = $palette->getColors()->toArray();
                
        return $this->handleView($this->view($colors, 200));
    }
    
}
