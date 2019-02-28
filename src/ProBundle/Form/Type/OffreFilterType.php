<?php

namespace ProBundle\Form\Type;

use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\DateFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\EntityFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\NumberFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\TextFilterType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OffreFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        
            ->add('dateAjout', DateFilterType::class)
            ->add('budget', NumberFilterType::class)
            ->add('Titre', TextFilterType::class)
            ->add('Description', TextFilterType::class)
            ->add('service', EntityFilterType::class, array('class' => 'ClientBundle\Entity\Service',
                'choice_label'=>'categorie',
                'multiple'=>false))
            ->add('User', EntityFilterType::class, array('class' => 'AppBundle\Entity\User',
                'choice_label'=>'username',
                'multiple'=>false))
            ->add('Client', EntityFilterType::class, array('class' => 'AppBundle\Entity\User',
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
            'data_class'        => 'ProBundle\Entity\Offre',
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
        return 'offre_filter';
    }
}
