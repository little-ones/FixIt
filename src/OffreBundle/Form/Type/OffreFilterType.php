<?php

namespace OffreBundle\Form\Type;

use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\DateRangeFilterType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\TextFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\NumberFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\EntityFilterType;

class OffreFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        
            ->add('budget', NumberFilterType::class)
            ->add('Titre', TextFilterType::class)
            ->add('Description', TextFilterType::class)
            ->add('service', EntityFilterType::class, array(
                'class' => 'ServiceBundle\Entity\Service',
                'choice_label'=>'nom',
                'multiple'=>false))
            ->add('User', EntityFilterType::class, array(
                'class' => 'AdminUserBundle\Entity\User' ,
                'choice_label'=>'username',
                'multiple'=>false))
            ->add('dateAjout',DateRangeFilterType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'        => 'OffreBundle\Entity\Offre',
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
