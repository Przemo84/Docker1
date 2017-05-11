<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LimiterForm extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Select', ChoiceType::class, array(
                'choices' => array(
                    '' => null,
                    1 => 1,
                    3 => 3,
                    5 => 5,
                    10 => 10,
                    15 => 15,
                    25 => 25,
                    50 => 50)
            ))
            ->add('Save',SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
                'data_class'=> 'AppBundle\Entity\Article',
            ]
        );
    }


}