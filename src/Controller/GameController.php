<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\RollDice;

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


        if (isset($_POST['roll']) && !empty($_POST['roll'])) {
            $diceRoll = new Rolldice();
            $diceRoll->setRollDice();
            return $this->redirectToRoute('game_progress', [
                'roll' => $diceRoll,
            ]);
        }

        return $this->render('game/progress.html.twig', []);
    }

    #[Route('/progress/dice', name: '_dice')]
    public function dice(): Response
    {
        return $this->redirectToRoute('game_progress', [
            'roll' => $diceRoll,
        ]);
    }

    #[Route('/collection', name: '_collection')]
    public function collection(): Response
    {
        return $this->render('game/collection.html.twig');
    }
}
