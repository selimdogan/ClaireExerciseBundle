<?php

namespace SimpleIT\ClaireAppBundle\Form\Course\Type;

use OC\CLAIRE\BusinessRules\Entities\Course\Course\DisplayLevel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class DisplayLevelType extends AbstractType
{
    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $displayLevels = array(
            DisplayLevel::SMALL  => 'petit',
            DisplayLevel::MEDIUM => 'moyen',
            DisplayLevel::BIG    => 'grand'
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

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'SimpleIT\ClaireAppBundle\Form\Course\Model\DisplayLevelModel',
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
