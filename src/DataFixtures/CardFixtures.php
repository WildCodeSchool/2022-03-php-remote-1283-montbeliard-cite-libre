<?php

namespace App\DataFixtures;

use App\Entity\Card;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CardFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $row = 0;


        $handle = fopen("assets/data/cartes.csv", "r");
        if ($handle !== false) {
            while (($data = fgetcsv($handle, null, ";")) !== false) {
                if ($row >  0) {
                    $card = new Card();
                    $card->setFamily($this->getReference('family_' . intval($data[0])));
                    $card->setName($data[1]);
                    $card->setDescription($data[2]);
                    $card->setImage($data[3]);
                    $card->setCredit(intval($data[4]));
                    $rule = ['association' => intval($data[5]), 'constraint' => $data[6],];
                    $card->setRule($rule);
                    $card->setCategory($this->getReference('category_' . intval($data[7])));
                    $manager->persist($card);
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
