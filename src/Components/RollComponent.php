<?php

namespace App\Components;

use App\Entity\Answer;
use App\Entity\Game;
use App\Entity\QuestionAsked;
use App\Repository\AnswerRepository;
use App\Repository\GameRepository;
use App\Repository\QuestionAskedRepository;
use App\Service\QuestionAsk;
use App\Service\RollDice;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('roll')]
class RollComponent extends AbstractController
{
    use DefaultActionTrait;

    public ?array $answer = null;
    public ?Game $game = null;
    public ?array $qAsked = null;

    #[LiveAction]
    public function setQuestion(
        RollDice $diceRoll,
        QuestionAsk $questionAsk,
        RequestStack $requestStack,
        AnswerRepository $answerRepository,
        GameRepository $gameRepository,
        QuestionAskedRepository $qAskedRepository
    ): void {
        $diceRoll->setRollDice();
        $roll = $diceRoll->getRoll();
        if ($roll === 1) {
            $questionAsk->apocalypse();
        } else {
            $questionAsk->rollQuestion($roll);
        }
        $session = $requestStack->getSession();
        $this->game = $gameRepository->findOneById($session->get('game')->getId());
        if ($session->has('question') and !empty($session->get('question'))) {
            $question = $session->get('question')->getID();
            $this->answer = $answerRepository->findBy(['id' => $question], ['id' => 'desc'], 1);
            $this->qAsked = $qAskedRepository->findBy(['game' => $this->game]);
        }
    }

    public function getAnswer(): ?array
    {
        return $this->answer ? $this->answer : null;
    }
}
