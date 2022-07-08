<?php

namespace App\Service;

use App\Entity\CardApocalypse;
use App\Repository\QuestionRepository;
use App\Repository\QuestionAskedRepository;
use App\Entity\QuestionAsked;
use App\Entity\Question;
use App\Entity\CardWon;
use App\Repository\CardApocalypseRepository;
use App\Repository\CardWonRepository;
use App\Repository\GameRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class QuestionAsk
{
    private QuestionRepository $questionRepository;
    private QuestionAskedRepository $qAskedRepository;
    private Question $question;
    private CardApocalypse $cardApocalypse;
    private RequestStack $requestStack;

    public function __construct(
        QuestionRepository $questionRepository,
        QuestionAskedRepository $qAskedRepository,
        RequestStack $requestStack,
        private CardApocalypseRepository $cardApoRepository,
        // private CardWonRepository $cardWonRepository,
        private GameRepository $gameRepository
    ) {
        $this->questionRepository = $questionRepository;
        $this->qAskedRepository = $qAskedRepository;
        $this->requestStack = $requestStack;
        $this->cardApoRepository = $cardApoRepository;
        // $this->cardWonRepository = $cardWonRepository;
        $this->gameRepository = $gameRepository;
    }

    public function rollQuestion(int $level): Question
    {
        $session = $this->requestStack->getSession();
        $gameId = $session->get('game')->getId();
        $this->question =  $this->questionRepository->selectRandomByLevel($level, $gameId)[0];
        $this->addQuestionAsked($this->question);
        return $this->question;
    }
    /**
     * Get the value of question
     */
    public function getQuestion(): Question
    {
        return $this->question;
    }

    public function apocalypse(): CardApocalypse
    {
        $session = $this->requestStack->getSession();
        $this->cardApocalypse = $this->cardApoRepository->selectRandom();
        // $cardWon = new CardWon();
        // $cardWon->setCardApocalypse($this->cardApocalypse);
        // $this->cardWonRepository->add($cardWon, true);
        $session->set('question', $this->cardApocalypse);

        return $this->cardApocalypse;
    }


    public function addQuestionAsked(Question $question): void
    {

        $session = $this->requestStack->getSession();
        $game = $this->gameRepository->findOneById($session->get('game')->getId());
        $questionAsked = new QuestionAsked();
        $questionAsked->setQuestion($question);
        $questionAsked->setGame($game);
        $game->setTurn($game->getTurn() + 1);
        $this->gameRepository->add($game, true);
        $this->qAskedRepository->add($questionAsked, true);
        $session->set('game', $game);
        $session->set('question', $question);
    }
}
