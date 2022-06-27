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

    #[Route('/progress', name: '_progress')]
    public function progress(): Response
    {
        return $this->render('game/progress.html.twig', [
            'controller_name' => 'GameController',
        ]);
    }

    #[Route('/collection', name: '_collection')]
    public function collection(): Response
    {
        return $this->render('game/collection.html.twig');
    }

    #[Route('/check', name: '_checking_answers', methods: ['POST'])]
    public function checkingTheAnswers(): Response
    {
        if ($_POST["verif"] == "true") {
            dd('bonne réponse');
        }
        if ($_POST["verif"] == "false") {
            dd('vaumaise réponse');
        }
        return $this->render('game/collection.html.twig');
    }
}
