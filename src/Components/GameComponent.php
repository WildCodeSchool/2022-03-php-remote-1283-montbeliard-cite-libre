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
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;

#[AsLiveComponent('game')]
class GameComponent extends AbstractController
{
    use DefaultActionTrait;

    private SessionInterface $session;

    #[LiveProp()]
    public bool $reRoll = false;

    #[LiveProp()]
    public bool $answered = false;

    #[LiveProp()]
    public bool $badAnswer = false;

    #[LiveProp()]
    public ?string $message = null;

    #[LiveProp()]
    public bool $gameWon = false;

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
    public function goodAnswer(): null|Response
    {

        $game = $this->gameRepository->findOneById($this->session->get('game')->getId());

        $cards = $this->cardRepository->selectRandomByNumber($this->session->get('roll'), $game);
        $this->pointsManager->pointsWon($cards, $game);

        $this->answered = !$this->answered;
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
        $this->answered = !$this->answered;
    }

    #[LiveAction]
    public function nextTurn(): void
    {
        $this->answered = !$this->answered;
    }
}
