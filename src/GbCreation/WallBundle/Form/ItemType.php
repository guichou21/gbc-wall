<?php

namespace GbCreation\WallBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fileToUpload')
            ->add('description')
            ->add('date','date',array('required' => true,
                                  'widget' =>'single_text',
                                  'format' =>'dd/MM/yyyy HH:mm'))
        ;
    }


    public function getName()
    {
        return 'gbcreation_wallbundle_itemtype';
    }
}
