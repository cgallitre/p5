<?php

namespace App\Form;

use App\Entity\Testimonial;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class TestimonialType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', TextareaType::class, [
                'label' => 'Texte du témoignage',
                'attr' => [
                    'placeholder' => 'Renseignez votre texte. Vous pouvez le formater avec la barre d\'outils ci-dessus.'
                ]
            ])
            ->add('author', TextType::class, [
                'label' => 'Votre signature',
                'attr' => [
                    'placeholder' => 'Laissez votre nom ainsi que votre fonction et entreprise'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Testimonial::class,
        ]);
    }
}
