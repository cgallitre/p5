<?php

namespace App\Form;

use App\Entity\UploadFile;
use Symfony\Component\Form\AbstractType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UploadFileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
           ->add('uploadFile', VichFileType::class, [
                'asset_helper' => true,
                'allow_delete' =>false,
                'download_uri' => false,
                'required' => false,
                'attr' => [
                   'placeholder' => 'Faire glisser un fichier ici',
                   'label' => 'Parcourir'
                ]
           ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UploadFile::class,
        ]);
    }
}
