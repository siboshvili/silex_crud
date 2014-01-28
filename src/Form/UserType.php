<?php

namespace Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('first_name', 'text', array(
                    'label' => 'სახელი',
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ))
                ->add('last_name', 'text', array(
                    'label' => 'გვარი',
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ))
                ->add('email', 'text', array(
                    'label' => 'ელ-ფოსტა',
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ))
                ->add('save', 'submit', array(
                    'attr' => array(
                        'class' => 'btn btn-default'
                    )
                ));
    }
    
    public function getName()
    {
        return 'user_form';
    }
}
