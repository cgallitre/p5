<?php

namespace App\Form;

use App\Entity\Testimonial;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class TestimonialType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', TextareaType::class, $this->getConfiguration('Texte du témoignage','Renseignez votre texte. Vous pouvez le formater avec la barre d\'outils ci-dessus.'))
            ->add('author', TextType::class, $this->getConfiguration("Votre signature","Laissez votre nom ainsi que votre fonction et entreprise"))
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Testimonial::class,
        ]);
    }
}
