<?php

namespace App\Service;

use App\Repository\QuestionRepository;
use App\Repository\QuestionAskedRepository;
use App\Entity\QuestionAsked;
use App\Entity\Question;

class QuestionAsk
{
    private QuestionRepository $questionRepository;
    private QuestionAskedRepository $questionAskedRepository;
    private Question $question;

    public function __construct(QuestionRepository $questionRepository, QuestionAskedRepository $questionAskedRepository)
    {
        $this->questionRepository = $questionRepository;
        $this->questionAskedRepository = $questionAskedRepository;
    }

    public function setQuestion($level)
    {
        $this->question =  $this->getQuestionRepository()->selectRandomByLevel($level)[0];
        return $this;
    }
    /**
     * Get the value of question
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Get the value of questionRepository
     */
    public function getQuestionRepository()
    {
        return $this->questionRepository;
    }

    public function addQuestionAsked()
    {
        $questionAsked = new QuestionAsked();
        $repository = $this->getQuestionAskedRepository();
        $questionAsked->setQuestion($this->getQuestion());
        $repository->add($questionAsked, true);
    }
    /**
     * Set the value of questionRepository
     *
     * @return  self
     */
    public function setQuestionRepository($questionRepository)
    {
        $this->questionRepository = $questionRepository;

        return $this;
    }

    /**
     * Get the value of questionAskedRepository
     */
    public function getQuestionAskedRepository()
    {
        return $this->questionAskedRepository;
    }

    /**
     * Set the value of questionAskedRepository
     *
     * @return  self
     */
    public function setQuestionAskedRepository($questionAskedRepository)
    {
        $this->questionAskedRepository = $questionAskedRepository;

        return $this;
    }
}
