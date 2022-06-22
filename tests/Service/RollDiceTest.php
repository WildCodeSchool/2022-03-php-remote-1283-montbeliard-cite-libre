<?php

namespace App\Tests\Service;

use App\Service\RollDice;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RollDiceTest extends KernelTestCase
{

    public function testGetRollDice(): void
    {
        $kernel = self::bootKernel();

        $container = static::getContainer();
        $roll = $container->get(RollDice::class);
        $roll->setRollDice();
        $this->assertIsInt($roll->getRollDice(), 'fake int');
    }
}
