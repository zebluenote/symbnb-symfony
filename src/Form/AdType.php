<?php

namespace App\Form;

use App\Entity\Ad;
use App\Form\ImageType;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AdType extends ApplicationType
{


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // cf. liste de tous les types de champ livrés avec Symfony (et leurs options) 
        // https://symfony.com/doc/current/reference/forms/types.html
        $builder
            ->add(
                'title', 
                null, 
                $this->getConfiguration("Titre", "Le titre de votre annonce")
            )
            ->add(
                'slug', 
                TextType::class, 
                $this->getConfiguration("Chaine URL", "Adresse web (automatique)", [
                    'required' => false
                ])
            )
            ->add(
                'coverImage', 
                UrlType::class, 
                $this->getConfiguration("URL de l'image principale", "URL de l'image principale")
            )
            ->add(
                'introduction', 
                null, 
                $this->getConfiguration("Introduction", "Une description générale de votre bien")
            )
            ->add(
                'content', 
                null, 
                $this->getConfiguration("Description détaillée", "Description détaillée de votre bien")
            )
            ->add(
                'rooms', 
                IntegerType::class, 
                $this->getConfiguration("Nombre de chambres", "Nb de chambres")
            )
            ->add(
                'price', 
                MoneyType::class, 
                $this->getConfiguration("Prix par nuit", "Indiquer le montant demandé pour une nuit")
            )
            ->add(
                'images', 
                CollectionType::class,
                [
                    'entry_type' => ImageType::class,
                    'allow_add' => true, // autoriser l'ajout de nouveaux éléments
                    'allow_delete' => true // autoriser l'ajout de nouveaux éléments
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
