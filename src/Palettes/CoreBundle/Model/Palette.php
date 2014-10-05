<?php

namespace Palettes\CoreBundle\Model;

use Palettes\CoreBundle\Model\om\BasePalette;

class Palette extends BasePalette
{
    public function isOwner(User $user) {
        return $this->getUserId() === $user->getId();
    }
}
