<?php

namespace App\Controller;

use App\Repository\AdRepository;
use App\Repository\UserRepository;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Gestion des articles
 * @package App\Controller
 */
class HomeController extends AbstractController
{

    /**
     * La page qui dit bonjour
     * @Route("/hello/{prenom}", name="hello")
     * @return Response 
     */
    public function hello($prenom = "anonyme")
    {
        return new Response('Bonjour ' . $prenom);
    }

    /**
     * @Route("/", name="homepage")
     * @return Response 
     * @throws LogicException 
     */
    public function home(AdRepository $adRepo, UserRepository $userRepo)
    {
        return $this->render(
            'home.html.twig', 
            [
                'ads' => $adRepo->findBestAds(3),
                'users' => $userRepo->findBestUsers(2)
            ]
        );
    }

}


