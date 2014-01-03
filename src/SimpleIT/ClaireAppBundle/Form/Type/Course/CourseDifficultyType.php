<?php

namespace SimpleIT\ClaireAppBundle\Form\Type\Course;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class CourseDifficultyType extends AbstractType
{
    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'difficulty',
            'textarea',
            array(
                'required'   => true,
                'max_length' => 255,
            )
        );
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'CourseDifficulty';
    }

}
