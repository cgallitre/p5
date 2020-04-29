<?php

namespace App\Form;

use App\Entity\User;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RegistrationType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, $this->getConfiguration("Prénom"))
            ->add('lastName', TextType::class, $this->getConfiguration("Nom"))
            ->add('company', TextType::class, $this->getConfiguration("Entreprise"))
            ->add('email', EmailType::class, $this->getConfiguration("Email"))
            ->add('hash', PasswordType::class, $this->getConfiguration("Mot de passe"))
            ->add('passwordConfirm', PasswordType::class, $this->getConfiguration("Confirmation de mot de passe"))
            ->add('project', TextareaType::class, $this->getConfiguration("Description du projet"))
            // Statut et Slug sont créés automatiquement au niveau de l'entité
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
