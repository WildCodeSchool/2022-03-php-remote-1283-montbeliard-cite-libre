<?php

namespace App\Service;

class RollDice
{
    private int $rollDice;

    public function getQuestion()
    {
    }

    /**
     * Get the value of rollDice
     */
    public function getRollDice()
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

        $this->rollDice =  rand(1, 6);;

        return $this;
    }
}
