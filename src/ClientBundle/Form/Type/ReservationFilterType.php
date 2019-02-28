<?php

namespace ClientBundle\Form\Type;

use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\DateFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\EntityFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\NumberFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\TextFilterType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        
            ->add('datedebut', DateFilterType::class)
            ->add('datefin', DateFilterType::class)
            ->add('budget', NumberFilterType::class)
            ->add('service', TextFilterType::class)
            ->add('etat', TextFilterType::class)
            ->add('idClient', EntityFilterType::class, array('class' => 'AppBundle\Entity\User',
                'choice_label'=>'username',
                'multiple'=>false))
            ->add('idPro', EntityFilterType::class, array('class' => 'AppBundle\Entity\User',
                'choice_label'=>'username',
                'multiple'=>false))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'        => 'ClientBundle\Entity\Reservation',
            'csrf_protection'   => false,
            'validation_groups' => array('filter'),
            'method'            => 'GET',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'reservation_filter';
    }
}
