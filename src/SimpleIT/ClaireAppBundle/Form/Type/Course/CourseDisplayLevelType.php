<?php

namespace SimpleIT\ClaireAppBundle\Form\Type\Course;

use SimpleIT\ApiResourcesBundle\Course\CourseResource;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class CourseDisplayLevelType
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class CourseDisplayLevelType extends AbstractType
{
    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $displayLevels = array(
            CourseResource::DISPLAY_LEVEL_SMALL  => 'Petit',
            CourseResource::DISPLAY_LEVEL_MEDIUM => 'Moyen',
            CourseResource::DISPLAY_LEVEL_BIG    => 'Grand'
        );
        $builder->add(
            'displayLevel',
            'choice',
            array(
                'choices'  => $displayLevels,
                'required' => true,
            )
        );
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'CourseDisplayLevel';
    }
}
