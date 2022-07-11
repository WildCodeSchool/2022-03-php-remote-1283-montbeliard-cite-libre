<?php

namespace App\DataFixtures;

use App\Entity\Family;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class FamilyFixtures extends Fixture
{
    public const FAMILIES = [
        "Métal",
        "Métiers du bois et
        de la poterie... et du sel",
        "La pierre et le verre",
        "La construction de l'habitat",
        "La construction des palais et cathédrales",
        "Les fabricants d'outils et d'accessoires",
        "Le travail des alliages (les petits objets)",
        "Travail des alliages (les gros objets)",
        "Les métiers autour de la farine",
        "Les métiers de bouche",
        "Les métiers du cuir et du textile",
        "Les métiers intellectuels et artistiques",
        "Les marchands",
        "Cavaliers de l'apocalypse",
    ];
    public function load(ObjectManager $manager): void
    {
        foreach (self::FAMILIES as $key => $value) {
            $family = new Family();
            $family->setName($value);
            $manager->persist($family);
            $this->addReference('family_' . $key, $family);
        }
        $manager->flush();
    }
}
