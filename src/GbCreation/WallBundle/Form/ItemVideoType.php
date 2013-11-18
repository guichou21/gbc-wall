<?php

namespace GbCreation\WallBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ItemVideoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file','text' ,array('label' => 'url'))
            ->add('description')
            ->add('date','date',array('required' => true,
                                  'widget' =>'single_text',
                                  'format' =>'dd/MM/yyyy HH:mm'))
        ;
    }


    public function getName()
    {
        return 'gbcreation_wallbundle_itemvideotype';
    }
}
