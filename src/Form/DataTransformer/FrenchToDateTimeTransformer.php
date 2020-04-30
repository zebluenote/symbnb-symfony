<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class FrenchToDateTimeTransformer implements DataTransformerInterface
{
    /**
     * On transforme une date au format DateTime en une date au format français
     * @param mixed $date 
     * @return mixed 
     */
    public function transform($date)
    {
        if($date === null) {
            return '';
        }

        return $date->format('d/m/Y');
    }

    public function reverseTransform($frenchDate)
    {
        if ($frenchDate === null){
            throw new TransformationFailedException("Aucune date fournie");
        }
        $date = \DateTime::createFromFormat('d/m/Y', $frenchDate);

        // Si l'on a rencontré un pb à l'exécution de createFromFormat
        // alors $date est null
        if ($date === false) {
            throw new TransformationFailedException("La date n'est pas au format attendu");
        }

        return $date;
    }
}
