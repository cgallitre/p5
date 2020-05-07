<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class AccountType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, $this->getConfiguration("Prénom"))
            ->add('lastName', TextType::class, $this->getConfiguration("Nom"))
            ->add('company', TextType::class, $this->getConfiguration("Entreprise"))
            ->add('email', EmailType::class, $this->getConfiguration("Email"))
            ->add('status', CheckboxType::class, [
                'label' => 'Compte actif',
                'required' => false,
                'attr' => [
                    'checked' => true
                ]
            ])
            ->add('slug')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
