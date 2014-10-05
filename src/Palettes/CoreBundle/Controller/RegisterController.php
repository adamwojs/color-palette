<?php

namespace Palettes\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Palettes\CoreBundle\Form\Type\UserRegisterType;
use Palettes\CoreBundle\Model\User;

/**
 * Kontroler obsługujący rejestrację użytkowników.
 *
 * @author Adam Wójs <adam@wojs.pl>
 * 
 * @Route("/register")
 */
class RegisterController extends Controller {
    
    /**
     * Wyświetla formularz rejestracji użytkownika.
     * 
     * @param Request $request
     * 
     * @Route("/", name="register")
     * @Method("GET")
     * @Template()
     */
    public function registerAction(Request $request) {
        $user = new User();
        $form = $this->createUserRegisterForm($user);
        
        return [
            'user' => $user,
            'form' => $form->createView()
        ];
    }
    
    /**
     * @param Request $request
     * 
     * @Route("/", name="register_submit")
     * @Method("POST")
     * @Template("PalettesCoreBundle:Register:register.html.twig")
     */
    public function submitAction(Request $request) {
        $user = new User();
        
        $form = $this->createUserRegisterForm($user);
        $form->handleRequest($request);
        
        if($form->isValid()) {
            // TODO: Obsługa potwierdzenia rejestracji.            
            $user->setPassword($this->encodePassword($user));
            $user->setRole('ROLE_USER');
            $user->save();
            
            return $this->redirect($this->generateUrl('_login'));
        }
        
        return [
            'user' => $user,
            'form' => $form->createView()
        ];
    }
    
    public function createUserRegisterForm(User $user) {
        $form = $this->createForm(new UserRegisterType(), $user, [
            'action' => $this->generateUrl('register_submit'),
            'method' => 'POST'
        ]);
        
        $form->add('submit', 'submit', [
            'label' => 'Zarejestruj się'
        ]);
        
        return $form;
    }
    
    private function encodePassword(User $user) {
        $encoder = $this
            ->get('security.encoder_factory')
            ->getEncoder($user);
        
        return $encoder->encodePassword($user->getPlainPassword(), $user->getSalt());
    }    
}
