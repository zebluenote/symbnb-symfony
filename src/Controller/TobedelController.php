<?php

namespace App\Controller;

use App\Entity\Tobedel;
use App\Form\TobedelType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TobedelController extends AbstractController
{
    /**
     * @Route("/tobedel", name="tobedel")
     */
    public function index()
    {
        $tobedel = new Tobedel();
        $form = $this->createForm(TobedelType::class, $tobedel);
        return $this->render('tobedel/index.html.twig', [
            'controller_name' => 'TobedelController',
            'form' => $form->createView()
        ]);
    }
}
