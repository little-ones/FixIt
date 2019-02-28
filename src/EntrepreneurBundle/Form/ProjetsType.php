<?php

namespace EntrepreneurBundle\Form;

use EntrepreneurBundle\Entity\Equipes;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjetsType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('titre')->add('proprietaire')->add('etat', ChoiceType::class, array(
            'choices'  => array(
                'En cours' => 'En cours',
                'Terminé' => 'Terminé')))->add('description')->add('duree')->add('equipe', EntityType::class , array(
            'class'=> Equipes::class,
            'choice_label'=>'titre',
        ))->add('ajouter', SubmitType::class);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'EntrepreneurBundle\Entity\Projets'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'entrepreneurbundle_projets';
    }


}
