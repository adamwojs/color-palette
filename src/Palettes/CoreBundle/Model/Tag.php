<?php

namespace Palettes\CoreBundle\Model;

use Palettes\CoreBundle\Model\om\BaseTag;

class Tag extends BaseTag
{
    public function __construct($name = null) {
        $this->setName($name);
    }
    
}
