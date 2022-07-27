<?php

namespace App\Service;

use App\Entity\Card;
use App\Entity\CardApocalypse;
use App\Entity\CardWon;

class LastTurnManager
{
    private int $pointWons = 0;
    private ?array $cardWons = null;
    private int $pointLost = 0;
    private ?array $cardLost = null;
    private ?array $cardApocalypses = null;

    public function getCardLost(): ?array
    {
        return $this->cardLost;
    }


    public function setCardLost(array $cardLost): self
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


    public function getCardWons(): ?array
    {
        return $this->cardWons;
    }


    public function setCardWons(array $cardWons): self
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


    public function getCardApocalypses(): ?array
    {
        return $this->cardApocalypses;
    }

    public function hasCardApocalypses(): bool
    {
        return (bool) $this->cardApocalypses;
    }

    public function setCardApocalypses(array $cardApocalypse): self
    {
        $this->cardApocalypses = $cardApocalypse;

        return $this;
    }

    public function addCardApocalypse(CardApocalypse $cardApocalypse): self
    {
        $this->cardApocalypses[] = $cardApocalypse;

        return $this;
    }
}
