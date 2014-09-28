<?php

namespace Palettes\CoreBundle\Form\Type;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ColorType extends BaseAbstractType
{
    protected $options = array(
        'data_class' => 'Palettes\CoreBundle\Model\Color',
        'name'       => 'color',  
    );

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name');
        $builder->add('value');
        $builder->add('paletteId', 'hidden'); 
    }
}
