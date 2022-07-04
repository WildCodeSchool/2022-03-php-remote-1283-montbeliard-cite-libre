<?php

namespace App\Controller;

use App\Repository\AnswerRepository;
use App\Repository\GameRepository;
use App\Service\RollDice;
use App\Service\QuestionAsk;
use App\Repository\QuestionRepository;
use App\Repository\QuestionAskedRepository;
use App\Service\GameManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;

#[Route('/game', name: 'game')]
class GameController extends AbstractController
{
    #[Route('/', name: '_index')]
    public function index(RequestStack $requestStack): Response
    {
        $session = $requestStack->getSession();
        if ($session->has('game')) {
            return $this->redirectToRoute('game_progress', []);
        }
        return $this->render('game/index.html.twig', [
            'controller_name' => 'GameController',
        ]);
    }

    #[Route('/new', name: '_new')]
    public function new(RequestStack $requestStack, GameManager $gameManager): Response
    {
        $session = $requestStack->getSession();
        if ($session->has('game')) {
            return $this->redirectToRoute('game_progress', []);
        }
        $gameManager->setGame();
        return $this->render('game/progress.html.twig', []);
    }

    #[Route('/progress', name: '_progress')]
    public function progress(
        RequestStack $requestStack,
        AnswerRepository $answerRepository,
        GameRepository $gameRepository,
        QuestionAskedRepository $qAskedRepository
    ): Response {
        $session = $requestStack->getSession();
        $answer = null;
        $qAsked = null;
        $game = $gameRepository->findOneById($session->get('game')->getId());
        if ($session->has('question')) {
            $question = $session->get('question')->getID();
            $answer = $answerRepository->findBy(['id' => $question], ['id' => 'desc'], 1);
            $qAsked = $qAskedRepository->findBy(['game' => $game]);
        }
        return $this->render('game/progress.html.twig', [
            'answer' => $answer,
            'game' => $game,
            'qAsked' => $qAsked
        ]);
    }



    #[Route('/progress/dice', name: '_dice')]
    public function dice(RollDice $diceRoll, QuestionAsk $question): Response
    {
        $diceRoll->setRollDice();
        $roll = $diceRoll->getRoll();
        $question->rollQuestion($roll);

        return $this->redirectToRoute('game_progress');
    }

    #[Route('/collection', name: '_collection')]
    public function collection(): Response
    {
        return $this->render('game/collection.html.twig');
    }
    #[Route('/reset', name: '_reset')]
    public function reset(RequestStack $requestStack): Response
    {
        $session = $requestStack->getSession();
        $session->invalidate();
        return $this->redirectToRoute('game_index');
    }
}
