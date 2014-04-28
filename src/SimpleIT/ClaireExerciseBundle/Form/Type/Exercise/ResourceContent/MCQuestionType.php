<?php

namespace SimpleIT\ClaireExerciseBundle\Form\Type\Exercise\ResourceContent;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class PictureType
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class MCQuestionType extends AbstractType
{
    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'question',
            'text'
        );
        $builder->add(
            'comment',
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
