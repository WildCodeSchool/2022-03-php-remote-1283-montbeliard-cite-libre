<?php

namespace App\Service;

class RollDice
{
    private int $rollDice;

    /**
     * Get the value of rollDice
     */
    public function getRollDice(): int
    {
        return $this->rollDice;
    }

    /**
     * Set the value of rollDice
     *
     * @return  self
     */
    public function setRollDice()
    {
        $this->rollDice = rand(1, 6);

        return $this;
    }
}
