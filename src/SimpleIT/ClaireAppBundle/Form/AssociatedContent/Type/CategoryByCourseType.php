<?php

namespace SimpleIT\ClaireAppBundle\Form\AssociatedContent\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class CategoryByCourseType extends AbstractType
{
    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $categories = array(1 => 'Informatique', 2 => 'Sciences', 3 => 'Entreprise');

        $builder->add(
            'categoryId',
            'choice',
            array(
                'choices'  => $categories,
                'required' => true,
                'disabled' => $options['disabled']
            )
        );
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'SimpleIT\ClaireAppBundle\Form\AssociatedContent\Model\CategoryByCourseModel',
                'disabled'   => false
            )
        );
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'CategoryByCourse';
    }
}
