<?php

namespace App\Controller;

use App\Entity\Ad;
use LogicException;
use App\Form\AdType;
use App\Repository\AdRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminAdController extends AbstractController
{
    /**
     * @Route("/admin/ads", name="admin_ads_index")
     */
    public function index(AdRepository $repo)
    {
        return $this->render('admin/ad/index.html.twig', [
            'ads' => $repo->findAll()
        ]);
    }

    /**
     * Permet d'afficher le formulaire d'édition d'une annonce
     * 
     * @Route("/admin/ads/{id}/edit", name="admin_ads_edit")
     * 
     * @param Ad $ad 
     * @return Response 
     * @throws LogicException 
     */
    public function edit(Ad $ad, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(AdType::class, $ad);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($ad);
            $manager->flush();
            $this->addFlash(
                'success',
                "L'annonce <strong>{$ad->getTitle()}</strong> a bien été modifiée"
            );
        }

        return $this->render('admin/ad/edit.html.twig', [
            'ad' => $ad,
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer une annonce
     * 
     * @Route("/admi/ads/{id}/delete", name="admin_ads_delete")
     * 
     * @param Ad $ad 
     * @param EntityManagerInterface $manager 
     * @return Response
     */
    public function delete(Ad $ad, EntityManagerInterface $manager)
    {
        // Si des réservations ont déjà été faites pour cette annonce
        // alors on ne veut pas la supprimer
        if (count($ad->getBookings()) > 0) {
            $this->addFlash(
                'warning',
                "Il n'est pas possible de supprimer l'annonce <strong>{$ad->getTitle()}</strong> car des réservations lui sont rattachées."
            );
        } else {
            $manager->remove($ad);
            $manager->flush();
    
            $this->addFlash(
                'success',
                "L'annonce <strong>{$ad->getTitle()}</strong> a été supprimée"
            );
        }

        return $this->redirectToRoute('admin_ads_index');
    }
}
