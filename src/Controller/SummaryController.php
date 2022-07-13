<?php

namespace App\Controller;

use App\Entity\Game;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SummaryController extends AbstractController
{
    #[Route('/summary/{id}', name: 'summary')]
    public function index(Game $game): Response
    {
        return $this->render('summary/index.html.twig', [
            'game' => $game,
        ]);
    }
}
