<?php

namespace App\Controller;

use App\Entity\Game;
use App\Form\GameType;
use DateTimeImmutable;
use App\Service\RollDice;
use App\Service\GameManager;
use App\Service\QuestionAsk;
use App\Service\PointsManager;
use App\Repository\CardRepository;
use App\Repository\GameRepository;
use App\Repository\AnswerRepository;
use App\Repository\CardWonRepository;
use App\Repository\QuestionRepository;
use App\Repository\QuestionAskedRepository;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_USER')]
#[Route('/game', name: 'game')]
class GameController extends AbstractController
{
    #[Route('/', name: '_index')]
    public function index(RequestStack $requestStack, Request $request, GameManager $gameManager): Response
    {
        $session = $requestStack->getSession();
        if ($session->has('game')) {
            return $this->redirectToRoute('game_progress', []);
        }

        $game = new Game();
        $form = $this->createForm(GameType::class, $game);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $gameManager->setGame($game);
            return $this->redirectToRoute('game_progress');
        }

        return $this->renderForm('game/index.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/progress', name: '_progress')]
    public function progress(
        RequestStack $requestStack,
        AnswerRepository $answerRepository,
        GameRepository $gameRepository,
        QuestionAskedRepository $qAskedRepository
    ): Response {
        $session = $requestStack->getSession();
        $game = $gameRepository->findOneById($session->get('game')->getId());

        if ($session->get('game')->getScore() >= 1000) {
            $date = new DateTimeImmutable();
            $game->setDuration(($game->getStartedAt()->diff($date, absolute: true)
            )->format('%H heure %I minutes %S secondes'));
            $gameRepository->add($game, true);
            return $this->render('confetties/index.html.twig');
        }
        $answer = null;
        $qAsked = null;
        if ($session->has('question') and !empty($session->get('question'))) {
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
    public function dice(RollDice $diceRoll, QuestionAsk $questionAsk): Response
    {
        $diceRoll->setRollDice();
        $roll = $diceRoll->getRoll();
        if ($roll === 1) {
            $questionAsk->apocalypse();
        } else {
            $questionAsk->rollQuestion($roll);
        }
        return $this->redirectToRoute('game_progress', []);
    }

    #[Route('/collection', name: '_collection')]
    public function collection(
        CardRepository $cardRepository,
        CardWonRepository $cardWonRepository,
        GameRepository $gameRepository,
        RequestStack $requestStack,
    ): Response {
        $session = $requestStack->getSession();
        $game = $gameRepository->findOneById($session->get('game')->getId());
        $cards = $cardRepository->findAll();
        $cardWons = $cardWonRepository->findBy(['game' => $game]);

        $cardWonsIds = [];
        foreach ($cardWons as $cardWon) {
            $cardWonsIds[] = $cardWon->getCard()->getId();
        }

        return $this->render('game/collection.html.twig', [
            'cards' => $cards,
            'cardWons' => $cardWons,
            'cardWonsIds' => $cardWonsIds,
        ]);
    }

    #[Route('/check/{answer}', name: '_calculate_score')]
    public function checkingTheAnswers(
        string $answer,
        PointsManager $pointsManager,
        CardRepository $cardRepository,
        RequestStack $requestStack,
        GameRepository $gameRepository,
    ): Response {

        if ($answer !== "false") {
            $session = $requestStack->getSession();
            $game = $gameRepository->findOneById($session->get('game')->getId());
            if ($session->get('roll') === 1) {
                $pointsManager->lostPoints($session->get('apocalypse'), $game);
            } else {
                $cards = $cardRepository->selectRandomByNumber($session->get('roll'), $game);
                $pointsManager->pointsWon($cards, $game);
            }
        }
        return $this->redirectToRoute('game_progress');
    }

    #[Route('/reset', name: '_reset')]
    public function reset(RequestStack $requestStack, GameRepository $gameRepository): Response
    {
        $session = $requestStack->getSession();
        $game = $gameRepository->findOneById($session->get('game')->getId());
        $gameRepository->remove($game, true);
        $session->invalidate();
        return $this->redirectToRoute('game_index');
    }

    #[Route('/win', name: '_confettis')]
    public function win(): Response
    {
        return $this->render('confetties/index.html.twig');
    }

    #[Route('/endGame', name: '_endGame')]
    public function endGame(RequestStack $requestStack, GameRepository $gameRepository): Response
    {
        $session = $requestStack->getSession();
        $game = $gameRepository->findOneById($session->get('game')->getId());
        $session->invalidate();
        return $this->redirectToRoute(
            'summary',
            ['id' => $game->getId()]
        );
    }
}
