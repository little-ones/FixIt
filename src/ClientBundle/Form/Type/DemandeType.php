<?php

namespace ClientBundle\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DemandeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        
            ->add('titre')
            ->add('categorie',EntityType::class,array(
                'class'=>'ClientBundle:Service',
                'choice_label'=>'categorie',
                'multiple'=>false) )
            ->add('idClient',EntityType::class,array(
                'class'=>'AppBundle\Entity\User',
                'choice_label'=>'username',
                'multiple'=>false) )
            ->add('budget',NumberType::class,['attr' => ['placeholder' => 'Insèrer des numéros svp !!!']])
            ->add('description',TextareaType::class);

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ClientBundle\Entity\Demande',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'demande';
    }
}
