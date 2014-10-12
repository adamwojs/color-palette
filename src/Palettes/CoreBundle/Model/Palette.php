<?php

namespace Palettes\CoreBundle\Model;

use Palettes\CoreBundle\Model\om\BasePalette;

class Palette extends BasePalette
{
    /**
     * Przypisuje tagi do palety.
     *  
     * @param array $tags Lista tagów
     */
    public function assignTags(array $tags) {
        $this->initPaletteTags(true);
        
        foreach($tags as $tag) {
            if($tag->isNew()) {                
                $tag->save(); // (!) Nowy tag
            }
            
            $relation = new PaletteTag();
            $relation->setPalette($this);
            $relation->setTag($tag);
            
            $this->addPaletteTag($relation);
        }        
    }
    
    public function isOwner(User $user) {
        return $this->getUserId() === $user->getId();
    }    
}
