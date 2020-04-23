<?php

namespace App\Form;

use App\Entity\Portfolio;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PortfolioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre de la référence'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description'
            ])
            ->add('technology', TextType::class, [
                'label' => 'Technologies / Langages utilisés'
            ])
            ->add('coverImage', UrlType::class, [
                'label' => 'Capture d\'écran'
            ])
            ->add('url', UrlType::class, [
                'label' => 'Lien vers la démonstration'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Portfolio::class,
        ]);
    }
}
