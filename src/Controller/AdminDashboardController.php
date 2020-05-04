<?php

namespace App\Controller;

use App\Service\StatsService;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\NonUniqueResultException;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminDashboardController extends AbstractController
{
    /**
     * Récupération des statistiques via le service StatsService
     * 
     * @Route("/admin", name="admin_dashboard")
     * 
     * @param EntityManagerInterface $manager 
     * @param StatsService $statsService 
     * @return Response 
     * @throws NoResultException 
     * @throws NonUniqueResultException 
     * @throws LogicException 
     */
    public function index(StatsService $statsService)
    {

        $stats = $statsService->getStats();

        return $this->render('admin/dashboard/index.html.twig', [
            'stats' => $stats
        ]);
    }
}
