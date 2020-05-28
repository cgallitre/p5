<?php

namespace App\Form;

use App\Entity\Project;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProjectType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->getConfiguration("Titre du projet"))
            ->add(
                'content', 
                 TextareaType::class, 
                 $this->getConfiguration("Description (markdown supporté)", "Echéance, contenu, acteurs, etc.",[
                     'attr' => [
                         'rows' => 10
                         ]
                 ]))
            ->add('finished', CheckboxType::class, [
                'label' => 'Projet terminé',
                'required' => false
            ])
            // ->add('users')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
