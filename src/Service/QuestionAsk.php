<?php

namespace App\Service;

use App\Repository\QuestionRepository;
use App\Repository\QuestionAskedRepository;
use App\Entity\QuestionAsked;
use App\Entity\Question;

class QuestionAsk
{
    private QuestionRepository $questionRepository;
    private QuestionAskedRepository $qAskedRepository;
    private Question $question;

    public function __construct(
        QuestionRepository $questionRepository,
        QuestionAskedRepository $qAskedRepository
    ) {
        $this->questionRepository = $questionRepository;
        $this->qAskedRepository = $qAskedRepository;
    }

    public function setQuestion(int $level): QuestionAsk
    {
        $this->question =  $this->questionRepository->selectRandomByLevel($level)[0];
        return $this;
    }
    /**
     * Get the value of question
     */
    public function getQuestion(): Question
    {
        return $this->question;
    }


    public function addQuestionAsked(): void
    {
        $questionAsked = new QuestionAsked();
        $questionAsked->setQuestion($this->getQuestion());
        $this->qAskedRepository->add($questionAsked, true);
    }
}
