<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;

class ApplicationType extends AbstractType
{
    /**
     * Génère la config pour chaque champ du formulaire
     *
     * @param string $label
     * @param string $placeholder
     * @param array $options
     * @return array 
     */
    protected function getConfiguration($label, $placeholder=null, $options = []){
        return array_merge([
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder
            ]
            ], $options) ;
    }
}