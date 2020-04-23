<?php

namespace App\Form;

use App\Entity\Training;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class TrainingType extends AbstractType
{
    /**
     * Génère la config pour chaque champ du formulaire
     *
     * @param string $label
     * @param string $placeholder
     * @param array $options
     * @return array 
     */
    private function getConfiguration($label, $placeholder=null, $options = []){
        return array_merge([
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder
            ]
            ], $options) ;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->getConfiguration("Titre", "Nom de la formation"))
            ->add('slug', TextType::class, $this->getConfiguration("URL", "Générée automatiquement si omis", [
                'required' => false
            ]))
            ->add('excerpt', TextType::class, $this->getConfiguration("Présentation", "Résumé de la formation"))
            ->add('duration', TextType::class, $this->getConfiguration("Durée de la formation", "En jours ou heures"))
            ->add('objectives', TextareaType::class, $this->getConfiguration("Objectifs pédagogiques"))
            ->add('level', TextareaType::class, $this->getConfiguration("Niveau requis", "Indiquer les prérequis pour suivre la formation"))
            ->add('public', TextareaType::class, $this->getConfiguration("Public"))
            ->add('program', TextareaType::class, $this->getConfiguration("Programme (markdown supporté)","Thèmes de la formation et contenu détaillé"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Training::class,
        ]);
    }
}
