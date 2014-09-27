<?php

namespace Palettes\CoreBundle\Form\Type;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class PaletteType extends BaseAbstractType
{
    protected $options = array(
        'data_class' => 'Palettes\CoreBundle\Model\Palette',
        'name'       => 'palette',
    );

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name');
        $builder->add('description');
    }
}
