<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Answer;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class AnswerFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $row = 0;
        $handle = fopen("assets/data/question.csv", "r");
        if ($handle !== false) {
            while (($data = fgetcsv($handle, null, ";")) !== false) {
                if ($row >  0) {
                    $answer = new Answer();
                    $answer->setQuestion($this->getReference('question_' . $row));
                    $answer->setContent($data[2]);
                    $answer->setIsCorrect($data[3]);
                    $manager->persist($answer);
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
            QuestionFixtures::class,
        ];
    }
}
