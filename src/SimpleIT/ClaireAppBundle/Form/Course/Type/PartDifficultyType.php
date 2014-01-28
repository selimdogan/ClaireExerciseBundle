<?php

namespace SimpleIT\ClaireAppBundle\Form\Course\Type;

use OC\CLAIRE\BusinessRules\Entities\Course\Course\Difficulty;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class PartDifficultyType extends AbstractType
{
    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $difficulties = array(Difficulty::EASY   => 'Facile',
                              Difficulty::MEDIUM => 'Moyenne',
                              Difficulty::HARD   => 'Difficile'
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
