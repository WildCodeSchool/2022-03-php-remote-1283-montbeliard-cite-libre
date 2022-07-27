<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Game;
use DateTimeImmutable;
use App\Repository\GameRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class GameFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $datetime = new DateTimeImmutable();
        $game = new Game();
        $game->setUser($this->getReference('user_00'));
        $game->setName('testGame');
        $game->setStartedAt($datetime);
        $game->setEndedAt($datetime->modify('+100 minutes'));
        $interval = ($game->getEndedAt()->diff($datetime, absolute: true));
        $game->setDuration($interval->format('%H heure %I minutes %S secondes'));
        $game->setTurn(22);
        $game->setScore(150);
        $game->setClasse($this->getReference('classe_' . ClasseFixtures::CLASSES[0]));
        $manager->persist($game);
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
