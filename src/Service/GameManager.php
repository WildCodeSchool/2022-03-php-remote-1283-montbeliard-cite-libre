<?php

namespace App\Service;

use App\Entity\Game;
use App\Repository\CardApocalypseRepository;
use App\Repository\GameRepository;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;

class GameManager
{
    public function __construct(
        private GameRepository $gameRepository,
        private RequestStack $requestStack,
        private Security $security,
        private CardApocalypseRepository $cardApoRepository,
    ) {
    }

    public function setCookieEndAt(DateTimeImmutable $endAt, string $duration): void
    {
        setcookie('endedAt', strval($endAt->getTimestamp()), [
            'expires' => strtotime('+30 days'),
            'path' => '/'
        ]);
        setcookie('duration', $duration, [
            'expires' => strtotime('+30 days'),
            'path' => '/'
        ]);
    }

    public function setGame(Game $game): void
    {
        $session = $this->requestStack->getSession();
        $datetime = new DateTimeImmutable();
        $user = $this->security->getUser();
        $game->setStartedAt($datetime);
        $endAt = $datetime->modify('+ ' . $game->getDuration() . ' minutes');
        $this->setCookieEndAt($endAt, $game->getDuration());
        $game->setEndedAt($endAt);
        $interval = ($game->getEndedAt()->diff($datetime, absolute: true));
        $game->setTurn(1);
        $game->setUser($user);
        $game->setScore(0);
        $game->setDuration($interval->format('%I : %S'));
        $this->gameRepository->add($game, true);
        $apocalypses = $this->cardApoRepository->selectAllRandom();
        $session->set('apocalypses', $apocalypses);
        $session->set('game', $game);
    }
}
