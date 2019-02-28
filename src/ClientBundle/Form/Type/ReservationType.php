<?php

namespace ClientBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        
            ->add('datedebut')
            ->add('datefin')
            ->add('budget')
            ->add('service')
            ->add('etat')
            ->add('idClient')
            ->add('idPro')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ClientBundle\Entity\Reservation',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'reservation';
    }
}
