<?php

namespace App\Controller;

use App\Repository\CartRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexpageController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(CartRepository $cartRepository): Response
    {    
            return $this->render('indexpage/index.html.twig', [
                'listcardbyuser' => $cartRepository->findBy(['user' => $this->getUser()]),
            ]);
    }
}
