<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Repository\AdRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AdController extends AbstractController
{
    /**
     * @Route("/ads", name="ads_index")
     */
    public function index(AdRepository $repo)
    {
        $ads = $repo->findAll();

        return $this->render('ad/index.html.twig', [
            'ads' => $ads
        ]);
    }

    /**
     * permet de créer une  annonce
     * 
     * @Route("/ads/new", name="ads_create")
     * 
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $manager)
    {
        $ad = new Ad();

        $form = $this->createForm(AdType::class, $ad);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            // On commence par persister les images transmises en provenance du formulaire de saisie
            foreach($ad->getImages() as $image){
                $image->setAd($ad);
                $manager->persist($image);
            }

            $ad->setAuthor($this->getUser());

            $manager->persist($ad);
            $manager->flush();
            
            $this->addFlash('success', "L'annonce <strong>{$ad->getTitle()}</strong> a bien été enregistrée !");

            return $this->redirectToRoute('ads_show', ['slug' =>$ad->getSlug()]);
        }

        return $this->render('ad/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * permet d'afficher le formulaire d'édition d'une annonce
     * 
     * @Route("/ads/{slug}/edit", name="ads_edit")
     * 
     * @return Response
     */
    public function edit(Ad $ad, Request $request, EntityManagerInterface $manager)
    {

        $form = $this->createForm(AdType::class, $ad);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            foreach($ad->getImages() as $image){
                $image->setAd($ad);
                $manager->persist($image);
            }

            $manager->persist($ad);
            $manager->flush();
            
            $this->addFlash('success', "L'annonce <strong>{$ad->getTitle()}</strong> a bien été modifiée !");

            return $this->redirectToRoute('ads_show', ['slug' =>$ad->getSlug()]);
        }

        return $this->render('ad/edit.html.twig', [
            'ad' => $ad,
            'form' => $form->createView()
        ]);
    }

    /**
     * permet d'afficher une annonce
     * 
     * @Route("/ads/{slug}", name="ads_show")
     * 
     * @return Response
     */
    public function show($slug, Ad $ad)
    {
        // Grace au param converter de Symfony, 
        // il n'est pas utile d'injecter la dépendence vers le AdRepository
        // ni de lancer la recherche via findOneBy... 
        // il suffit de mentionner Ad $ad dans les arguments de la fonction show()
        // $ad = $repo->findOneBySlug($slug);

        return $this->render('ad/show.html.twig', [
            'ad' => $ad
        ]);
    }

}
