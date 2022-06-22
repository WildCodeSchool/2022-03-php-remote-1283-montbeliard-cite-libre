<?php

namespace App\Controller;

use App\Service\RollDice;
use App\Repository\QuestionAskedRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
    public function progress(QuestionAskedRepository $qAskedRepository): Response
    {
        $roll = null;
        if (isset($_GET['roll']) && !empty($_GET['roll'])) {
            $roll = $_GET['roll'];
        }

        return $this->render('game/progress.html.twig', [
            'roll' => $roll
        ]);
    }

    #[Route('/progress/dice', name: '_dice')]
    public function dice(RollDice $diceRoll): Response
    {
        $diceRoll->setRollDice();
        $roll = $diceRoll->getRollDice();
        return $this->redirectToRoute('game_progress', [
            'roll' => $roll,
        ]);
    }

    #[Route('/collection', name: '_collection')]
    public function collection(): Response
    {
        return $this->render('game/collection.html.twig');
    }
}
