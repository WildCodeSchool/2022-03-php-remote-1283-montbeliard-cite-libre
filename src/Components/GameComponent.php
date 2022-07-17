<?php

namespace App\Components;

use App\Entity\Answer;
use App\Entity\Question;
use App\Repository\AnswerRepository;
use App\Repository\CardRepository;
use App\Repository\GameRepository;
use App\Service\PointsManager;
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
    use DefaultActionTrait;

    private SessionInterface $session;

    #[LiveProp()]
    public bool $reRoll = false;

    #[LiveProp()]
    public bool $answered = false;

    #[LiveProp()]
    public ?string $message = null;


    public function __construct(
        private RollDice $diceRoll,
        private QuestionAsk $questionAsk,
        RequestStack $requestStack,
        private AnswerRepository $answerRepository,
        private GameRepository $gameRepository,
        private PointsManager $pointsManager,
        private CardRepository $cardRepository
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
        $this->answered = !$this->answered;

        $this->message = null;
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


    #[LiveAction]
    public function goodAnswer(): void
    {

        $game = $this->gameRepository->findOneById($this->session->get('game')->getId());
        if ($this->session->get('roll') === 1) {
            $this->pointsManager->lostPoints($this->session->get('apocalypse'), $game);
        } else {
            $cards = $this->cardRepository->selectRandomByNumber($this->session->get('roll'), $game);
            $this->pointsManager->pointsWon($cards, $game);
        }
        $this->answered = !$this->answered;
    }

    #[LiveAction]
    public function falseAnswer(): void
    {
        $this->message = "Mauvaise Réponse, relance le dé";
    }
}
