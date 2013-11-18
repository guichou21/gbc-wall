<?php

namespace GbCreation\WallBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CommentEditedType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('author')
            ->add('comment')
            ->add('isApproved', 'checkbox', array('required' => false))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GbCreation\WallBundle\Entity\Comment'
        ));
    }

    public function getName()
    {
        return 'gbcreation_wallbundle_commenteditedtype';
    }
}
