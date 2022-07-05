<?php

namespace App\DataFixtures;

use App\Entity\Classe;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ClasseFixtures extends Fixture
{
    public const CLASSES = [
        '6A', '6B', '6C',
        '5A', '5B', '5C',
        '4A', '4B', '4C',
        '3A', '3B', '3C',
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::CLASSES as $classe) {
            $classeStudent = new Classe();
            $classeStudent->setClasse($classe);
            $manager->persist($classeStudent);
            $this->addReference('classe_' . $classe, $classeStudent);
        }


        $manager->flush();
    }
}
