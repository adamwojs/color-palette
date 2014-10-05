<?php

namespace Palettes\CoreBundle\Model;

use Palettes\CoreBundle\Model\om\BaseColor;

class Color extends BaseColor
{
    public function isOwner(User $user) {
        return $this->getPalette()->getUserId() === $user->getId();
    }
}
