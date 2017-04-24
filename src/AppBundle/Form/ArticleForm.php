<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleForm extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', ' string')
            ->add('content', 'string');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
                'data_class'=> 'AppBundle\Entity\Article::class',
            ]
        );
    }

    /**
     * @return string The name of this type
     */
//    public function getName()
//    {
//        return 'article';
//    }


}