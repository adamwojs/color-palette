<?php

namespace Palettes\CoreBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Palettes\CoreBundle\Model\Tag;
use Palettes\CoreBundle\Model\TagQuery;

/**
 * Transformuje listy tagów oddzielonych przecinkiem do 
 * listy encji.
 *
 * @author Adam Wójs <adam@wojs.pl>
 */
class TagsListTransformer implements DataTransformerInterface {
    
    private $delimiter;
    
    public function __construct($delimiter = ',') {
        $this->delimiter = $delimiter;
    }
    
    public function reverseTransform($value) {
        if(!$value) {
            return null;
        }
        
        $tags = explode($this->delimiter, $value);
        
        return array_map(function($name) {
            $name = trim(strtolower($name));
            
            $tag = TagQuery::create()
                ->findOneByName($name);
            if(!$tag) {
                $tag = new Tag($name);
            }

            return $tag;
        }, $tags);
    }

    public function transform($tags) {
        if($tags == null) {
            return '';
        }
        
        return implode($this->delimiter, array_map(function($tag) {
            return $tag->name;
        }, $tags->toArray()));
    }
}
