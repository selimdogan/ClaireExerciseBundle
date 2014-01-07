<?php


namespace SimpleIT\ClaireAppBundle\Form\Type\Course;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class DescriptionMetadataType
 *
 * @author Romain Kuzniak <romain.kuzniak@simple-it.fr>
 */
class DescriptionMetadataType extends AbstractType
{
    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'value',
            'textarea',
            array(
                'required'   => true,
                'max_length' => 255,
            )
        );
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'DescriptionMetadata';
    }
}
