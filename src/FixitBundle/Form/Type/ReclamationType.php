<?php

namespace FixitBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReclamationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        
            ->add('Titre')
            ->add('Description')
            ->add('date')
            ->add('User')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FixitBundle\Entity\Reclamation',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'reclamation';
    }
}
