<?php

namespace GbCreation\WallBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ItemSearchType extends AbstractType
{
	  public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('begin', 'text', array('label' => 'Begin','mapped'   => false))
        		->add('nb', 'text', array('label' => 'Nb','mapped'   => false))
        ;
    }

    public function getName()
    {
        return 'gbcreation_wallbundle_itemsearchtype';
    }
}

