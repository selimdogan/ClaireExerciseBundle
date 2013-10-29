<?php


namespace SimpleIT\ClaireAppBundle\Form\Type\AssociatedContent;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class CategoryByCourseType
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class CategoryByCourseType extends AbstractType
{
    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'id',
            'choice',
            array(
                'choices'  => array(1 => 'Informatique', 2 => 'Sciences', 3 => 'Entreprise'),
                'required' => true,
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
