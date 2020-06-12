<?php

namespace App\Form;

use App\Entity\Theme;
use App\Entity\Training;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class TrainingType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->getConfiguration("Titre", "Nom de la formation"))
            ->add('slug', TextType::class, $this->getConfiguration("URL", "Générée automatiquement si omis", [
                'required' => false
            ]))
            ->add('excerpt', TextareaType::class, $this->getConfiguration("Présentation", "Résumé de la formation"))
            ->add('duration', TextType::class, $this->getConfiguration("Durée de la formation", "En jours ou heures"))
            ->add('objectives', TextareaType::class, $this->getConfiguration("Objectifs pédagogiques", null, [
                'required' => false
            ]))
            ->add('level', TextareaType::class, $this->getConfiguration("Niveau requis", "Indiquer les prérequis pour suivre la formation", null, [
                'required' => false
            ]))
            ->add('public', TextareaType::class, $this->getConfiguration("Public", null, [
                'required' => false
            ]))
            ->add('program', TextareaType::class, [
                'label' => 'Programme (markdown supporté)',
                'attr' => [
                    'placeholder' => 'Thèmes de la formation et contenu détaillé',
                    'rows' => 10
                    ]]
                )
            ->add('theme', EntityType::class, [
                'class' => Theme::class,
                'choice_label' => 'title',
                'label' => 'Thème de la formation',
                'expanded' =>true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Training::class,
        ]);
    }
}
