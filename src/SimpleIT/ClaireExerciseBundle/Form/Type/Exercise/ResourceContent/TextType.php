<?php

namespace SimpleIT\ClaireExerciseBundle\Form\Type\Exercise\ResourceContent;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class TextType
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class TextType extends AbstractType
{
    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'text',
            'text'
        );
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'ResourceType';
    }
}
