<?php

namespace SimpleIT\ClaireAppBundle\Form\Course\Type;

use OC\CLAIRE\BusinessRules\Entities\Difficulty\Difficulty;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class DifficultyType extends AbstractType
{
    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $difficulties = array(
            Difficulty::EASY   => "Facile",
            Difficulty::MEDIUM => "Moyen",
            Difficulty::HARD   => "Difficile"
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

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'difficulty';
    }

}
