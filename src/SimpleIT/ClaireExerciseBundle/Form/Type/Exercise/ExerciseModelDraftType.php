<?php

namespace SimpleIT\ClaireExerciseBundle\Form\Type\Exercise;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ExerciseModelDraftType
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ExerciseModelDraftType extends AbstractType
{
    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'draft',
            'checkbox',
            array('required' => false)
        );
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'ExerciseModelDraft';
    }
}
