<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Game;
use DateTime;
use DateTimeImmutable;

class GameFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $datetime = new DateTimeImmutable();
        $game = new Game();
        $game->setName('testGame');
        $game->setStartedAt($datetime);
        $game->setEndedAt($datetime->modify('+100 minutes'));
        $interval = ($game->getEndedAt()->diff($datetime, absolute: true));
        $game->setDuration($interval->format('%H heure %I minutes %S secondes'));
        $manager->persist($game);
        $manager->flush();
    }
}
