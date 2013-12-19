<?php

namespace SimpleIT\ClaireAppBundle\Form\Type\Exercise;

use SimpleIT\ApiResourcesBundle\Course\CourseResource;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource\CommonResource;
use SimpleIT\ApiResourcesBundle\Exercise\ResourceResource;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class OwnerExerciseModelPublicType
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class OwnerExerciseModelPublicType extends AbstractType
{
    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $types = array(
            true => 'public',
            false => 'privé',
        );

        $builder->add(
            'public',
            'choice',
            array(
                'choices'  => $types,
                'required' => true,
            )
        );
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'OwnerExerciseModelPublic';
    }
}
