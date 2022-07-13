<?php

namespace App\Service;

use App\Entity\Card;
use App\Entity\CardApocalypse;
use App\Entity\CardWon;

class LastTurnManager
{
    private int $pointWons;
    private Card $cardWons;
    private int $pointLost;
    private CardWon $cardLost;
    private CardApocalypse $cardApocalypse;

    public function getCardLost(): CardWon
    {
        return $this->cardLost;
    }


    public function setCardLost(CardWon $cardLost): self
    {
        $this->cardLost = $cardLost;

        return $this;
    }

    public function getPointLost(): int
    {
        return $this->pointLost;
    }


    public function setPointLost(int $pointLost): self
    {
        $this->pointLost = $pointLost;

        return $this;
    }


    public function getCardWons(): Card
    {
        return $this->cardWons;
    }


    public function setCardWons(Card $cardWons): self
    {
        $this->cardWons = $cardWons;

        return $this;
    }


    public function getPointWons(): int
    {
        return $this->pointWons;
    }

    public function setPointWons(int $pointWons): self
    {
        $this->pointWons = $pointWons;

        return $this;
    }


    public function getCardApocalypse(): CardApocalypse
    {
        return $this->cardApocalypse;
    }

    public function setCardApocalypse(CardApocalypse $cardApocalypse): self
    {
        $this->cardApocalypse = $cardApocalypse;

        return $this;
    }
}
