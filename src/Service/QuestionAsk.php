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
use Symfony\Component\VarDumper\VarDumper;

class QuestionAsk
{
    private QuestionRepository $questionRepository;
    private QuestionAskedRepository $qAskedRepository;
    private Question|string $question;
    private CardApocalypse $cardApocalypse;
    private RequestStack $requestStack;

    public function __construct(
        QuestionRepository $questionRepository,
        QuestionAskedRepository $qAskedRepository,
        RequestStack $requestStack,
        private GameRepository $gameRepository
    ) {
        $this->questionRepository = $questionRepository;
        $this->qAskedRepository = $qAskedRepository;
        $this->requestStack = $requestStack;
        $this->gameRepository = $gameRepository;
    }

    public function rollQuestion(int $level): Question|string
    {
        $session = $this->requestStack->getSession();
        $gameId = $session->get('game')->getId();
        $session->set('alert', null);
        if (!isset($this->questionRepository->selectRandomByLevel($level, $gameId)[0])) {
            $this->question = "Plus aucune question de disponible pour ce niveau";
            $session->set('question', null);
            $session->set('alert', $this->question);
            return $this->question;
        }
        $this->question =  $this->questionRepository->selectRandomByLevel($level, $gameId)[0];
        $this->addQuestionAsked($this->question);
        return $this->question;
    }

    public function addQuestionAsked(Question $question): void
    {

        $session = $this->requestStack->getSession();
        $game = $this->gameRepository->findOneById($session->get('game')->getId());
        $questionAsked = new QuestionAsked();
        $questionAsked->setQuestion($question);
        $questionAsked->setGame($game);
        $this->addTurn();
        $this->qAskedRepository->add($questionAsked, true);
        $session->set('question', $question);
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
        $apocalypses = $session->get('apocalypses');
        $this->cardApocalypse = $apocalypses[0];
        $session->set('apocalypse', $apocalypses[0]);
        $session->set('question', null);
        $this->replaceCardApocalypse();
        $this->addTurn();
        return $this->cardApocalypse;
    }

    public function replaceCardApocalypse(): void
    {
        $session = $this->requestStack->getSession();
        $apocalypses = $session->get('apocalypses');
        $firstCard = array_shift($apocalypses);
        $apocalypses[] = $firstCard;
        $session->set('apocalypses', $apocalypses);
    }
    public function addTurn(): void
    {
        $session = $this->requestStack->getSession();
        $game = $this->gameRepository->findOneById($session->get('game')->getId());
        $game->setTurn($game->getTurn() + 1);
        $this->gameRepository->add($game, true);
        $session->set('game', $game);
    }
}
