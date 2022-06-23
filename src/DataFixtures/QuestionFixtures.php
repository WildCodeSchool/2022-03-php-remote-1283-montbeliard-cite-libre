<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Question;

class QuestionFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $row = 0;
        $handle = fopen("assets/data/question.csv", "r");
        if ($handle !== false) {
            while (($data = fgetcsv($handle, null, ";")) !== false) {
                if ($row >  0) {
                    $question = new Question();
                    $question->setQuestion($data[0]);
                    $question->setLevel($data[1]);
                    $manager->persist($question);
                    $this->addReference('question_' . $row, $question);
                }
                $row++;
            }
            fclose($handle);
        }
        $manager->flush();
    }
}
