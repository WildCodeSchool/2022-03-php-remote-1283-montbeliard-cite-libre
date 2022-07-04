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
        private RollDice $rollDice,
        private CardApocalypseRepository $cardApocalypseRepository,
        private CardWonRepository $cardWonRepository
    ) {
        $this->questionRepository = $questionRepository;
        $this->qAskedRepository = $qAskedRepository;
        $this->requestStack = $requestStack;
        $this->rollDice = $rollDice;
        $this->cardApocalypseRepository = $cardApocalypseRepository;
        $this->cardWonRepository = $cardWonRepository;
    }

    public function rollQuestion(int $level): Question
    {
        $this->question =  $this->questionRepository->selectRandomByLevel($level)[0];
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
        $this->cardApocalypse = $this->cardApocalypseRepository->selectRandom()[0];
        $cardWon = new CardWon();
        $cardWon->setCardApocalypse($this->cardApocalypse);
        $this->cardWonRepository->add($cardWon, true);
        $session->set('question', $this->cardApocalypse);

        return $this->cardApocalypse;
    }
    public function addQuestionAsked(Question $question): void
    {
        $session = $this->requestStack->getSession();
        $questionAsked = new QuestionAsked();
        $questionAsked->setQuestion($question);
        $this->qAskedRepository->add($questionAsked, true);
        $session->set('question', $question);
    }
}
