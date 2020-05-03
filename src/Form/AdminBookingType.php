<?php

namespace App\Form;

use App\Entity\Ad;
use App\Entity\User;
use App\Entity\Booking;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AdminBookingType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Attention : booker est un champ de type EntityType
        $builder
            ->add(
                'startDate', 
                DateType::class, 
                $this->getConfiguration(
                    "Date de début", 
                    "",
                    ['widget' => 'single_text']
                )
            )
            ->add(
                'endDate', 
                DateType::class, 
                $this->getConfiguration(
                    "Date de fin", 
                    "",
                    ['widget' => 'single_text']
                )
            )
            ->add(
                'comment', 
                TextareaType::class,
                $this->getConfiguration(
                    "Commentaire du client",
                    "",
                    ['attr' => ['rows' => 5]]
                )
            )
            ->add(
                'booker', 
                EntityType::class, 
                $this->getConfiguration(
                    "Le client réservataire",
                    "",
                    [
                        'class' => User::class, 
                        // 'choice_label' => 'fullName',
                        'choice_label' => function($user) {
                            return $user->getFirstName() . " " . strtoupper($user->getLastName());
                        }
                    ]
                )
            )
            ->add(
                'ad', 
                EntityType::class, 
                $this->getConfiguration(
                    "L'annonce correspondant à cette réservation",
                    "",
                    [
                        'class' => Ad::class,
                        'choice_label' => function($ad) {
                            return $ad->getId() .  " - " . $ad->getTitle();
                        }
                    ]
                )
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
            'validation_groups' => ["Default"]
        ]);
    }
}
