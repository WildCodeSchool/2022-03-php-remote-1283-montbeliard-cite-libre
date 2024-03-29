<?php

namespace App\Components;

use App\Entity\Answer;
use App\Entity\CardWon;
use App\Entity\Question;
use App\Entity\QuestionAsked;
use App\Repository\AnswerRepository;
use App\Repository\CardRepository;
use App\Repository\CardWonRepository;
use App\Repository\GameRepository;
use App\Repository\QuestionAskedRepository;
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

    #[LiveProp]
    public ?array $cardsWons = null;

    public ?bool $answerIsCorrect = false;

    public function __construct(
        protected RollDice $diceRoll,
        protected QuestionAsk $questionAsk,
        RequestStack $requestStack,
        protected AnswerRepository $answerRepository,
        protected GameRepository $gameRepository,
        protected PointsManager $pointsManager,
        protected CardWonRepository $cardWonRepository,
        protected CardRepository $cardRepository,
        private QuestionAskedRepository $questionAskedRepo,
    ) {
        $this->session = $requestStack->getSession();
    }

    public function getRoll(): null|int
    {
        $game = $this->gameRepository->findOneById($this->session->get('game')->getId());

        $this->cardsWons = $this->cardWonRepository->findBy(['game' => $game], ['id' => 'DESC'], 10);
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

        $this->cardsWons = $this->cardWonRepository->findBy(['game' => $game], ['id' => 'DESC'], 10);
        $this->questionAsk->unsetQuestion();
        return null;
    }

    #[LiveAction]
    public function falseAnswer(): void
    {
        $this->message = "Mauvaise Réponse, relance le dé";
        $this->badAnswer = true;
        $this->answered = true;
        $this->questionAsk->unsetQuestion();
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
        $array = $this->answerRepository->findBy([
            'question' => $this->getQuestion()
        ]);
        shuffle($array);
        return $this->getQuestion() ?
            $array : false;
    }

    #[LiveAction]
    public function setSoloAnswer(#[LiveArg] int $id): void
    {
        $this->answerIsCorrect = (bool) $this->answerRepository->findOneBy([
            'id' => $id,
            'question' => $this->getQuestion(),
            'isCorrect' => true
        ]);

        $questionAsked = $this->questionAskedRepo->findOneBy([
            'game' => $this->session->get('game')->getId(),
            'question' => $this->getQuestion()
        ]);
        $questionAsked->setAnswerQcm($this->answerRepository->find($id));
        $this->questionAskedRepo->add($questionAsked, true);
        if ($this->answerIsCorrect) {
            $this->goodAnswer();
        } else {
            $this->falseAnswer();
        }
    }
}
