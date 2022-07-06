<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\CardApocalypse;

class CardApocalypseFixtures extends Fixture
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
                    $apocalypse->setType($data[0]);
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
}
