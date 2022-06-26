<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;

class RollDice
{
    private int $rollDice;
    private RequestStack $requestStack;
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }
    /**
     * Get the value of rollDice
     */
    public function getRoll(): int
    {
        $session =  $this->requestStack->getSession();
        return $session->get('roll');
    }


    public function setRollDice(): int
    {
        $session =  $this->requestStack->getSession();
        $this->rollDice = rand(1, 6);
        $session->set('roll', $this->rollDice);
        $session->set('game', 'active');

        return $this->rollDice;
    }
}
