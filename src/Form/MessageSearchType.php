<?php

namespace App\Form;

use App\Entity\Type;
use App\Entity\User;
use App\Entity\Project;
use App\Entity\MessageSearch;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class MessageSearchType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('keyword', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => "Objet, message ou auteur"
                ]
            ])
            ->add('type', EntityType::class, [
                'required' => false,
                'label' => false,
                'class' => Type::class,
                'choice_label' => 'title',
                'expanded'=>true,
                'multiple'=>false,
                'placeholder' => 'Aucun'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MessageSearch::class,
            'csrf_protection' => false,
            'method' => 'GET' // permet de partager la recherche via l'URL
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }


}
