<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ScoreController extends AbstractController
{
    #[Route('/score', name: 'app_score')]
    public function index(): Response
    {
        return $this->render('score/index.html.twig', [
            'controller_name' => 'ScoreController',
        ]);
    }
}
