<?php

namespace SimpleIT\ClaireExerciseBundle\Form\Type\Exercise;

use SimpleIT\ClaireExerciseResourceBundle\Model\Resources\ExerciseModel\Common\CommonModel;
use SimpleIT\ClaireExerciseResourceBundle\Model\Resources\ResourceResource;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ExerciseModelTypeType
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ExerciseModelTypeType extends AbstractType
{
    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $types = array(
            CommonModel::MULTIPLE_CHOICE=> 'QCM',
            CommonModel::GROUP_ITEMS  => 'Groupement',
            CommonModel::ORDER_ITEMS => 'Ordonnancement',
            CommonModel::PAIR_ITEMS=> 'Appariement'
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
        return 'ExerciseModelType';
    }
}
