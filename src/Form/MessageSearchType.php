<?php

namespace App\Form;

use App\Entity\Type;
use App\Entity\Project;
use App\Entity\MessageSearch;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MessageSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('project', EntityType::class, [
                'required' => false,
                'class' => Project::class,
                'choice_label' => 'title',
                'label' => false,
                'expanded'=>true,
                'multiple'=>false,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('pr')
                        ->where('pr.finished = false');
                }
            
            ])
            ->add('type', EntityType::class, [
                'required' => false,
                'label' => false,
                'class' => Type::class,
                'choice_label' => 'title',
                'expanded'=>true,
                'multiple'=>false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MessageSearch::class,
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
