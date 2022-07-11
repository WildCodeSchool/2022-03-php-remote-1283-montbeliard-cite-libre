<?php

namespace App\Components;

use App\Entity\Answer;
use App\Entity\Question;
use App\Repository\AnswerRepository;
use App\Repository\GameRepository;
use App\Repository\QuestionAskedRepository;
use App\Service\QuestionAsk;
use App\Service\RollDice;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;

#[AsLiveComponent('game')]
class GameComponent
{
    private SessionInterface $session;

    #[LiveProp()]
    public bool $reRoll = false;

    use DefaultActionTrait;

    public function __construct(
        private RollDice $diceRoll,
        private QuestionAsk $questionAsk,
        private RequestStack $requestStack,
        private AnswerRepository $answerRepository,
        private GameRepository $gameRepository,
        private QuestionAskedRepository $qAskedRepository
    ) {
        $this->session = $requestStack->getSession();
    }

    #[LiveAction]
    public function setQuestion(): void
    {
        $this->diceRoll->setRollDice();
        $roll = $this->diceRoll->getRoll();
        if ($roll === 1) {
            $this->questionAsk->apocalypse();
        } else {
            $this->questionAsk->rollQuestion($roll);
        }
        $this->reRoll = !$this->reRoll;
    }

    public function getQuestion(): ?Question
    {
        return $this->session->has('question') &&
            !empty($this->session->get('question')) ?
            $this->session->get('question') : null;
    }

    public function getAnswer(): false|Answer
    {
        return $this->session->has('question') &&
            !empty($this->session->get('question')) ?
            $this->answerRepository->findOneBy([
                'id' => $this->session->get('question')->getID(),
                'isCorrect' => true
            ]) : false;
    }

    public function getRoll(): null|int
    {
        return $this->session->has('roll') ? $this->session->get('roll') : null;
    }
}
