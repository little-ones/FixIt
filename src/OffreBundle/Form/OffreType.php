<?php

namespace OffreBundle\Form;

use OffreBundle\Entity\Offre;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OffreType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('budget')
            ->add('Titre')
            ->add('Description', TextareaType::class)
            ->add('service', EntityType::class, array(
                'class' => 'ServiceBundle\Entity\Service',
                'choice_label'=>'nom',
                'multiple'=>false))
            ->add('Client', EntityType::class, array(
                'class' => 'AdminUserBundle\Entity\User',
                'choice_label'=>'username',
                'multiple'=>false))
            ->add('Ajouter',SubmitType::class);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Offre::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'offrebundle_offre';
    }


}
