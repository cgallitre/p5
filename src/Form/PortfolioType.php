<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Portfolio;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
            ->add('url', UrlType::class, $this->getConfiguration("Lien vers la démonstration",null, [
                'required' => false
            ]))
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'title',
                'label' => 'Catégorie de la référence',
                'expanded' =>true
            ])
            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'allow_delete' => true,
                'label' => 'Capture d\'écran (320x200, 16/10)',
                'download_uri' => false,
                'image_uri' => true,
                'asset_helper' => true,
                'attr' => [
                    'placeholder' => false
                ]
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
