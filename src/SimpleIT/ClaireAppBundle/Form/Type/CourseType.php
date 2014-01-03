<?php

namespace SimpleIT\ClaireAppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CourseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('content', 'textarea', array(
            'attr' => array(
                'class' => 'tinymce editor',
                'data-theme' => 'advanced' // simple, advanced, bbcode
            )
        ));

    }

    public function getName()
    {
        return 'course';
    }
}
