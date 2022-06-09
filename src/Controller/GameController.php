<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/game', name: 'game')]
class GameController extends AbstractController
{
    #[Route('/', name: '_index')]
    public function index(): Response
    {
        return $this->render('game/index.html.twig', [
            'controller_name' => 'GameController',
        ]);
    }
}
