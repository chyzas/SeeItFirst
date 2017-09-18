<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class FirstQueryType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('url', 'text', [
                'label' => 'filter_form.query_url',
                'attr' => [
                    'placeholder' => 'filter_form.query_url'
                ],
            ])
            ->add('name', 'text', [
                'label' => 'filter_form.query_name',
                'attr' => [
                    'placeholder' => 'filter_form.query_name'
                ],
            ])
            ->add('email', 'email', [
                'label' => 'main.email',
                'required' => false,
                'attr' => [
                    'placeholder' => 'main.email'
                ],
            ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'first_query';
    }
}
