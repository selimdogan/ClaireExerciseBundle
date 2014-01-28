<?php

namespace SimpleIT\ClaireAppBundle\Form\Course\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class PartDescriptionType extends AbstractType
{
    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'description',
            'textarea',
            array(
                'required'   => true,
                'max_length' => 255,
            )
        );
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'SimpleIT\ClaireAppBundle\Form\Course\Model\PartDescriptionModel',
            )
        );
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'PartDescription';
    }
}
