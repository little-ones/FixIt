<?php

namespace CabinetBundle\Form;

use blackknight467\StarRatingBundle\Form\RatingType;
use CabinetBundle\Entity\Evenement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EvenementType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('prix')->add('lieu')->add('date')->add('nbreParticipant')->add('rating',RatingType::class, [
            'label' => 'Rating'
        ])->add('nom')->add('idUser')->add('formation');
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Evenement::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'cabinetbundle_evenement';
    }


}
