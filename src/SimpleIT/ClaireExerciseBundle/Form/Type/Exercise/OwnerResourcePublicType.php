<?php

namespace SimpleIT\ClaireExerciseBundle\Form\Type\Exercise;

use SimpleIT\ClaireExerciseResourceBundle\Model\Resources\ExerciseResource\CommonResource;
use SimpleIT\ClaireExerciseResourceBundle\Model\Resources\ResourceResource;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class OwnerResourcePublicType
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class OwnerResourcePublicType extends AbstractType
{
    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $types = array(
            true => 'Ressource publique',
            false => 'Ressource privée',
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
        return 'OwnerResourcePublic';
    }
}
