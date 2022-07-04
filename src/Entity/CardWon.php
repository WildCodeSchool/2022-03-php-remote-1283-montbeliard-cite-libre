<?php

namespace App\Entity;

use App\Repository\CardWonRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CardWonRepository::class)]
class CardWon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Game::class, inversedBy: 'cardWons')]
    private ?Game $game;

    #[ORM\ManyToOne(targetEntity: CardApocalypse::class, inversedBy: 'cardWons')]
    private ?CardApocalypse $cardApocalypse;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): self
    {
        $this->game = $game;

        return $this;
    }

    public function getCardApocalypse(): ?CardApocalypse
    {
        return $this->cardApocalypse;
    }

    public function setCardApocalypse(?CardApocalypse $cardApocalypse): self
    {
        $this->cardApocalypse = $cardApocalypse;

        return $this;
    }
}
