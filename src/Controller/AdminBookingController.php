<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\AdminBookingType;
use App\Repository\BookingRepository;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminBookingController extends AbstractController
{
    /**
     * Permet d'afficher la liste des réservations
     * 
     * @Route("/admin/bookings", name="admin_bookings_index")
     * 
     * @param BookingRepository $repo 
     * @return Response 
     * @throws LogicException 
     */
    public function index(BookingRepository $repo)
    {
        $bookings = $repo->findAll();

        return $this->render('admin/booking/index.html.twig', [
            'controller_name' => 'AdminBookingController',
            'bookings' => $bookings
        ]);
    }

    /**
     * Permet de modifier une réservation
     * 
     * @Route("admin/booking/{id}/edit", name="admin_booking_edit")
     * 
     * @return Response 
     * @throws LogicException 
     */
    public function edit(Booking $booking, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(AdminBookingType::class, $booking, [
            'validation_groups' => ["Default"]
        ]);

        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() ) {

            // En cas de modification du logement (donc de l'annonce liée à cette réservation)
            // alors il faut recalculer le montant de la réservation.
            // On pourrait le faire ici avec :
            // $booking->setAmount( $booking->getAd()->getPrice() * $booking->getDuration() );
            // Mais il est préférable de faire jouer le PrePersist précédemment défini sur la calcul du montant
            // en passant ici le montant à 0 (ce qui va déclencher le recalcul par le PrePersist _auquel on aura ajouté un PreUpdate !_)
            $booking->setAmount(0);

            // En principe ce persist n'est pas nécessaire 
            // car on est en train d'éditer un booking qui existe donc déja
            // on pourrait se contenter du flush()
            $manager->persist($booking); 
            $manager->flush();

            $this->addFlash(
                'success',
                "La réservation n°{$booking->getId()} a été modifiée avec succès"
            );

            return $this->redirectToRoute("admin_bookings_index");
        }

        return $this->render('admin/booking/edit.html.twig', [
            'form' => $form->createView(),
            'booking' => $booking
        ]);
    }

    /**
     * Permet de supprimer une réservation
     * 
     * @Route("admin/booking/{id}/delete", name="admin_booking_delete")
     * 
     * @param mixed $booking 
     * @param EntityManagerInterface $manager 
     * @return RedirectResponse 
     * @throws LogicException 
     */
    public function delete(Booking $booking, EntityManagerInterface $manager)
    {
        $bookingId = $booking->getId();
        $bookingTitle = $booking->getAd()->getTitle();

        $manager->remove($booking);
        $manager->flush();

        $this->addFlash(
            'success',
            "La réservation n°{$bookingId} pour l'annonce <strong>{$bookingTitle}</strong> a été supprimée"
        );

        return $this->redirectToRoute("admin_bookings_index");
    }
}
