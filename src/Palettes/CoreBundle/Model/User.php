<?php

namespace Palettes\CoreBundle\Model;

use Palettes\CoreBundle\Model\om\BaseUser;
use Symfony\Component\Security\Core\User\UserInterface;


class User extends BaseUser implements UserInterface
{
    private $plainPassword;
    
    public function getPlainPassword() {
        return $this->plainPassword;
    }
    
    public function setPlainPassword($password) {
        $this->plainPassword = $password;
    }
    
    public function eraseCredentials() {
        $this->plainPassword = '';
    }

    public function getRoles() {
        return [ $this->getRole() ];
    }
}
