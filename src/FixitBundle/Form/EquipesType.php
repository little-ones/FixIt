<?php

namespace FixitBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FixitBundle\Entity\Equipes;

class EquipesType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('titre')->add('membres')->add('etat', ChoiceType::class, array(
            'choices'  => array(
                'Complète' => 'Complète',
                'Incomplète' => 'Incomplète',
            )))->add('disponibilite', ChoiceType::class, array(
            'choices'  => array(
                'Disponible' => 'Disponible',
                'Indispoible' => 'Indisponible',
            )))->add('ajouter', SubmitType::class);
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Equipes::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'fixitbundle_equipes';
    }


}
