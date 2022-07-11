<?php

namespace App\DataFixtures;

use App\Entity\CardApocalypse;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CardApocalypseFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $row = 0;

        $handle = fopen("assets/data/apocalypse.csv", "r");
        if ($handle) {
            while (($data = fgetcsv($handle, null, ";"))) {
                if ($row >  0) {
                    $rule = [
                        'type' => $data[4], 'category' => $data[5], 'value' => intval($data[6]),
                        'exception' => intval($data[7])
                    ];
                    $apocalypse = new CardApocalypse();
                    $apocalypse->setFamily($this->getReference('family_' . intval($data[0])));
                    $apocalypse->setName($data[1]);
                    $apocalypse->setDescription($data[2]);
                    $apocalypse->setImage($data[3]);
                    $apocalypse->setRule($rule);
                    $manager->persist($apocalypse);
                }
                $row++;
            }
            fclose($handle);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            FamilyFixtures::class,
        ];
    }
}
