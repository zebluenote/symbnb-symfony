<?php

namespace App\Form;

use App\Entity\Booking;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Form\DataTransformer\FrenchToDateTimeTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class BookingType extends ApplicationType
{

    private $transformer;

    public function __construct(FrenchToDateTimeTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'startDate', 
                TextType::class,
                $this->getConfiguration("Date d'arrivée", "la date à laquelle vous envisagez arriver")
            )
            ->add(
                'endDate', 
                TextType::class,
                $this->getConfiguration("Date de départ", "la date à laquelle vous souhaitez quitter le logement")
            )
            ->add(
                'comment', 
                TextareaType::class,
                $this->getConfiguration(false, "Une question, un petit message à transmettre à votre hôte...", ['required' => false])
            )
        ;

        $builder->get('startDate')->addModelTransformer($this->transformer);
        $builder->get('endDate')->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
