<?php

namespace Palettes\CoreBundle\Form\Type;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Formularz rejestracji użytkownika.
 *
 * @author Adam Wójs <adam@wojs.pl>
 */
class UserRegisterType extends BaseAbstractType {
    
    protected $options = array(
        'data_class' => 'Palettes\CoreBundle\Model\User',
        'name'       => 'register',
    );

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('username');
        $builder->add('plainPassword', 'repeated', [
            'type' => 'password',
            'required' => true,
            'invalid_message' => 'The password fields must match.',
            'options' => array('attr' => array('class' => 'password-field')),            
            'first_options'  => array('label' => 'Password'),
            'second_options' => array('label' => 'Repeat Password'),            
        ]);
        $builder->add('email', 'email');
    }
}
