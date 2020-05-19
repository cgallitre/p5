<?php

namespace App\Form;

use App\Entity\Type;
use App\Entity\Message;
use App\Entity\Project;
use App\Form\UploadFileType;
use App\Form\ApplicationType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;


class MessageType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('project', EntityType::class, [
                'class' => Project::class,
                'choice_label' => 'title',
                'label' => 'Projet',
                'expanded' =>false,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                                ->where('p.finished = false')
                              ->orderBy('p.id', 'DESC');
                }
            ])
            ->add('title', TextType::class, $this->getConfiguration("Objet"))
            ->add('content', TextareaType::class, [
                'label'=>'Message',
                'attr'=>[
                    'rows'=>8
                ]
            ])
            ->add('type', EntityType::class, [
                'class' => Type::class,
                'choice_label' => 'title',
                'label' => 'Type de message',
                'expanded' =>true
            ])
            ->add('uploadFiles', CollectionType::class, [
                'entry_type' => UploadFileType::class,
                'allow_add' => true,
                'allow_delete' => true, 
                'label' => false
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Message::class,
        ]);
    }
}
