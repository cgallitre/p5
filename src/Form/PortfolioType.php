<?php

namespace App\Form;

use App\Entity\Portfolio;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PortfolioType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->getConfiguration("Titre de la référence"))
            ->add('description', TextareaType::class, $this->getConfiguration("Description"))
            ->add('technology', TextType::class, $this->getConfiguration("Technologies / Langages utilisés"))
            ->add('coverImage', UrlType::class, $this->getConfiguration("Capture d'écran"))
            ->add('url', UrlType::class, $this->getConfiguration("Lien vers la démonstration"))
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Portfolio::class,
        ]);
    }
}
