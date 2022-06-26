<?php

namespace App\Controller;

use App\Service\RollDice;
use App\Service\QuestionAsk;
use App\Repository\QuestionRepository;
use App\Repository\QuestionAskedRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;

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
        return $this->render('game/progress.html.twig', []);
    }



    #[Route('/progress/dice', name: '_dice')]
    public function dice(RollDice $diceRoll, QuestionAsk $question): Response
    {
        $diceRoll->setRollDice();
        $roll = $diceRoll->getRoll();
        $question->rollQuestion($roll);

        return $this->redirectToRoute('game_progress', []);
    }

    #[Route('/collection', name: '_collection')]
    public function collection(): Response
    {
        return $this->render('game/collection.html.twig');
    }
}
