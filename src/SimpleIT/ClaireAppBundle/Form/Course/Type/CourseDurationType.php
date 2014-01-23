<?php

namespace SimpleIT\ClaireAppBundle\Form\Course\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class CourseDurationType extends AbstractType
{
    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $durations = array(
            'empty_value' => '',
            'PT10M'       => 'course.duration.10minutes',
            'PT30M'       => 'course.duration.30minutes',
            'PT1H'        => 'course.duration.1hour',
            'PT2H'        => 'course.duration.2hours',
            'PT4H'        => 'course.duration.4hours',
            'P1D'         => 'course.duration.1day',
            'P2D'         => 'course.duration.2days',
            'P1W'         => 'course.duration.1week',
            'P2W'         => 'course.duration.2weeks',
            'P1M'         => 'course.duration.1month',
            'P2M'         => 'course.duration.2months'
        );
        $builder->add(
            'duration',
            'choice',
            array(
                'choices'            => $durations,
                'required'           => true,
                'translation_domain' => 'course'
            )
        );
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'SimpleIT\ClaireAppBundle\Form\Course\Model\CourseDurationModel',
            )
        );
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'CourseDuration';
    }
}
