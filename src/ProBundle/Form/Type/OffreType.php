<?php

namespace ProBundle\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
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
        
            ->add('dateAjout')
            ->add('budget')
            ->add('Titre')
            ->add('Description')
            ->add('service',EntityType::class,
                array(
                    'class'=>'ClientBundle\Entity\Service',
                    'choice_label'=>'categorie',
                    'multiple'=>false))
            ->add('User',EntityType::class,
                array(
                    'class'=>'AppBundle\Entity\User',
                    'choice_label'=>'username',
                    'multiple'=>false))
            ->add('Client',
                EntityType::class,
                array(
                    'class'=>'AppBundle\Entity\User',
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
            'data_class' => 'ProBundle\Entity\Offre',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'offre';
    }
}
