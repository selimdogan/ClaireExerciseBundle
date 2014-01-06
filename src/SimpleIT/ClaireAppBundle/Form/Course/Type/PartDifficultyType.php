<?php

namespace SimpleIT\ClaireAppBundle\Form\Course\Type;

use OC\CLAIRE\BusinessRules\Entities\Difficulty\Difficulty;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class PartDifficultyType
{
    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $difficulties = array(Difficulty::EASY   => 'facile',
                              Difficulty::MEDIUM => 'moyen',
                              Difficulty::HARD   => 'difficile'
        );
        $builder->add(
            'difficulty',
            'choice',
            array(
                'choices'  => $difficulties,
                'required' => true,
            )
        );
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                'data_class' => 'SimpleIT\ClaireAppBundle\Form\Course\Model\PartDifficultyModel',
            ));
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'PartDifficulty';
    }
}
