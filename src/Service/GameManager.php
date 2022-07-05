<?php

namespace App\Service;

use App\Entity\Game;
use App\Repository\GameRepository;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;

class GameManager
{
    public function __construct(
        private GameRepository $gameRepository,
        private RequestStack $requestStack,
        private Security $security
    ) {
        $this->gameRepository = $gameRepository;
        $this->requestStack = $requestStack;
        $this->security = $security;
    }

    public function setGame(Game $game): void
    {
        $session = $this->requestStack->getSession();
        $datetime = new DateTimeImmutable();

        //if (isset($_POST) && !empty($_POST))
        $user = $this->security->getUser();
//        $gameData = $_POST;
//        $game = new Game();
//        $game->setName($gameData['name']);
        $game->setStartedAt($datetime);
        $game->setEndedAt($datetime->modify('+ ' . $game->getDuration() . ' minutes'));
        $interval = ($game->getEndedAt()->diff($datetime, absolute: true));
//        $game->setType($gameData['type']);
        $game->setTurn(1);
        $game->setUser($user);
        $game->setScore(0);
        $game->setDuration($interval->format('%H heure %I minutes %S secondes'));
        $this->gameRepository->add($game, true);
        $session->set('game', $game);
    }

    public function checkGameType(): void
    {
        $session = $this->requestStack->getSession();

        if ($session->get('game') !== null && !empty($session->get('game'))) {
            $game = $session->get('game');
            $gameType = $game->getType();
            if ($gameType == 'mdj') {
            }
        }
    }
}
