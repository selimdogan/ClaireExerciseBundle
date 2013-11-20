<?php

namespace SimpleIT\ClaireAppBundle\Form\Type\Exercise;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ExerciseModelTitleType
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ExerciseModelTitleType extends AbstractType
{
    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'title',
            'text'
        );
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'ExerciseModelTitle';
    }
}
