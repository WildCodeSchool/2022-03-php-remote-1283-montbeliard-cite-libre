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
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;

#[AsLiveComponent('game')]
class GameComponent extends AbstractController
{
    use DefaultActionTrait;

    protected SessionInterface $session;

    #[LiveProp]
    public bool $reRoll = false;

    #[LiveProp]
    public bool $answered = false;

    #[LiveProp]
    public bool $badAnswer = false;

    #[LiveProp]
    public ?string $message = null;

    #[LiveProp]
    public bool $gameWon = false;

    public ?bool $answerIsCorrect = false;

    public function __construct(
        protected RollDice $diceRoll,
        protected QuestionAsk $questionAsk,
        RequestStack $requestStack,
        protected AnswerRepository $answerRepository,
        protected GameRepository $gameRepository,
        protected PointsManager $pointsManager,
        protected CardRepository $cardRepository
    ) {
        $this->session = $requestStack->getSession();
    }

    public function getRoll(): null|int
    {
        return $this->session->has('roll') ? $this->session->get('roll') : null;
    }

    #[LiveAction]
    public function goodAnswer(): null|Response
    {

        $game = $this->gameRepository->findOneById($this->session->get('game')->getId());

        $cards = $this->cardRepository->selectRandomByNumber($this->session->get('roll'), $game);
        $this->pointsManager->pointsWon($cards, $game);

        $this->answered = true;
        $this->answerIsCorrect = true;
        if ($game->getScore() >= 1000) {
            $date = new DateTimeImmutable();
            $game->setDuration(($game->getStartedAt()->diff($date, absolute: true)
            )->format('%H heure %I minutes %S secondes'));
            $this->gameRepository->add($game, true);
            $this->gameWon = true;
            return $this->redirectToRoute('game_confettis');
        }
        return null;
    }

    #[LiveAction]
    public function falseAnswer(): void
    {
        $this->message = "Mauvaise Réponse, relance le dé";
        $this->badAnswer = true;
        $this->answered = true;
    }

    #[LiveAction]
    public function nextTurn(): void
    {
        $this->answered = !$this->answered;
    }

    #[LiveAction]
    public function setQuestion(): void
    {
        $this->diceRoll->setRollDice();
        $roll = $this->diceRoll->getRoll();
        if ($roll === 1) {
            $this->questionAsk->apocalypse();
            $game = $this->gameRepository->findOneById($this->session->get('game')->getId());
            $this->pointsManager->lostPoints($this->session->get('apocalypse'), $game);
        } else {
            $this->questionAsk->rollQuestion($roll);
        }
        $this->reRoll = !$this->reRoll;
        $this->answered = false;
        $this->badAnswer = false;
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
        return $this->getQuestion() ?
            $this->answerRepository->findOneBy([
                'question' => $this->getQuestion(),
                'isCorrect' => true
            ]) : false;
    }

    public function getAnswers(): false|array
    {
        return $this->getQuestion() ?
            $this->answerRepository->findBy([
                'question' => $this->getQuestion()
            ]) : false;
    }

    #[LiveAction]
    public function setSoloAnswer(#[LiveArg] int $id): void
    {
        $this->answerIsCorrect = (bool) $this->answerRepository->findOneBy([
            'id' => $id,
            'question' => $this->getQuestion(),
            'isCorrect' => true
        ]);
        if ($this->answerIsCorrect) {
            $this->goodAnswer();
        } else {
            $this->falseAnswer();
        }
    }
}
