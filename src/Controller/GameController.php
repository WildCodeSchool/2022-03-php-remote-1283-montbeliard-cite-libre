<?php

namespace App\Controller;

use App\Service\PointsManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
    public function progress(RequestStack $requestStack): Response
    {
        $session = $requestStack->getSession();
        if (!$session->has('points')) {
            $session->set('points', 0);
        }
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
    public function checkingTheAnswers(PointsManager $pointsManager): Response
    {
        if ($_POST["verif"] == "true") {
            //requete bdd
            $pointsManager->calcuatePoints([]);//<= retour de la requete
        }
        return $this->redirectToRoute('game_progress');
    }
}
