<?php

namespace SimpleIT\ClaireExerciseBundle\Form\Type\Exercise;

use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource\CommonResource;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ResourceResource;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ResourceTypeType
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ResourceTypeType extends AbstractType
{
    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $types = array(
            CommonResource::MULTIPLE_CHOICE_QUESTION=> 'Question de QCM',
            CommonResource::PICTURE  => 'Image',
            CommonResource::TEXT => 'Texte',
            CommonResource::SEQUENCE => 'Séquence'
        );

        $builder->add(
            'type',
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
        return 'ResourceType';
    }
}
