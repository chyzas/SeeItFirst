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
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'col-sm-2 control-label']
            ])
            ->add('name', 'text', [
                'label' => 'filter_form.query_name',
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'col-sm-2 control-label']
            ])
            ->add('email', 'email', [
                'label' => 'main.email',
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'col-sm-2 control-label']
            ])
            ->add('save', 'submit', array('label' => 'main.save', 'attr' => array('class'=>'btn-default')))
        ;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'first_query';
    }
}
