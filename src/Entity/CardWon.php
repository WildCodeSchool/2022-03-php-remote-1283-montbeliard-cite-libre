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

    #[ORM\ManyToOne(targetEntity: Game::class, inversedBy: 'cardWons', fetch:"EAGER")]
    private ?Game $game;

    #[ORM\ManyToOne(targetEntity: Card::class, inversedBy: 'cardWons', fetch:"EAGER")]
    private Card $card;

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

    public function getCard(): ?Card
    {
        return $this->card;
    }

    public function setCard(?Card $card): self
    {
        $this->card = $card;

        return $this;
    }
}
