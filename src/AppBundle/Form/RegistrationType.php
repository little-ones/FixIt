<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom');
        $builder->add('prenom')->add('roles', ChoiceType::class, [
            'mapped' => false,
            'choices'  => [
                'Client' => 'ROLE_CLIENT',
                'Professionnel' => 'ROLE_PRO',
                'Entrepreneur' => 'ROLE_ENTREPRENEUR',
                'Vendeur' => 'ROLE_VENDEUR',
            ],
        ])->add('adresse')->add('submit',SubmitType::class)->add('telephone');
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }

    public function getName()
    {
        return $this->getBlockPrefix();

    }
}