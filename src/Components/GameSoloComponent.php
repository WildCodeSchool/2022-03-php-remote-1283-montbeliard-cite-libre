<?php

namespace App\Components;

use App\Entity\Answer;
use App\Entity\Card;
use App\Repository\AnswerRepository;
use App\Repository\CardRepository;
use App\Repository\GameRepository;
use App\Service\PointsManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('game_solo')]
class GameSoloComponent
{
    use DefaultActionTrait;

    public bool $answerIsCorrect = false;
    private \Symfony\Component\HttpFoundation\Session\SessionInterface $session;

    public function __construct(
        private GameRepository $gameRepository,
        private AnswerRepository $answerRepository,
        private PointsManager $pointsManager,
        private CardRepository $cardRepository,
        RequestStack $requestStack
    ) {
        $this->session = $requestStack->getSession();
    }

    public function getAnswers(): false|array
    {
        return $this->session->has('question') &&
            !empty($this->session->get('question')) ?
            $this->answerRepository->findBy([
                'question' => $this->session->get('question')->getID()
            ]) : false;
    }

    #[LiveAction]
    public function answer(#[LiveArg] int $id): void
    {
        $this->answerIsCorrect = $this->answerRepository->find($id)->isIsCorrect();
        if ($this->answerIsCorrect) {
            $game = $this->gameRepository->findOneById($this->session->get('game')->getId());
            if ($this->session->get('roll') === 1) {
                $this->pointsManager->lostPoints($this->session->get('apocalypse'), $game);
            } else {
                $cards = $this->cardRepository->selectRandomByNumber($this->session->get('roll'), $game);
                $this->pointsManager->pointsWon($cards, $game);
            }
        }
    }
}
