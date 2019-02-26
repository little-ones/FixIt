<?php

namespace ClientBundle\Form\Type;

use ClientBundle\Entity\Demande;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\ChoiceFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\DateFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\DateRangeFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\EntityFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\TextFilterType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DemandeFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        
            ->add('titre', TextFilterType::class)
            ->add('budget', TextFilterType::class)
            ->add('date', DateRangeFilterType::class)
            ->add('categorie', EntityFilterType::class, array('class' => 'ClientBundle\Entity\Service',
                'choice_label'=>'categorie',
                'multiple'=>false))
            ->add('idClient', EntityFilterType::class, array('class' => 'AppBundle\Entity\User',
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
            'data_class'        => Demande::class,
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
        return 'demande_filter';
    }
}
