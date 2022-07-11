<?php

namespace App\Entity;

use App\Repository\FamilyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FamilyRepository::class)]
class Family
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\OneToMany(mappedBy: 'family', targetEntity: Card::class)]
    private Collection $cards;

    #[ORM\OneToMany(mappedBy: 'family', targetEntity: CardApocalypse::class)]
    private Collection $cardApocalypses;

    public function __construct()
    {
        $this->cards = new ArrayCollection();
        $this->cardApocalypses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Card>
     */
    public function getCards(): Collection
    {
        return $this->cards;
    }

    public function addCard(Card $card): self
    {
        if (!$this->cards->contains($card)) {
            $this->cards[] = $card;
            $card->setFamily($this);
        }

        return $this;
    }

    public function removeCard(Card $card): self
    {
        if ($this->cards->removeElement($card)) {
            // set the owning side to null (unless already changed)
            if ($card->getFamily() === $this) {
                $card->setFamily(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CardApocalypse>
     */
    public function getCardApocalypses(): Collection
    {
        return $this->cardApocalypses;
    }

    public function addCardApocalypse(CardApocalypse $cardApocalypse): self
    {
        if (!$this->cardApocalypses->contains($cardApocalypse)) {
            $this->cardApocalypses[] = $cardApocalypse;
            $cardApocalypse->setFamily($this);
        }

        return $this;
    }

    public function removeCardApocalypse(CardApocalypse $cardApocalypse): self
    {
        if ($this->cardApocalypses->removeElement($cardApocalypse)) {
            // set the owning side to null (unless already changed)
            if ($cardApocalypse->getFamily() === $this) {
                $cardApocalypse->setFamily(null);
            }
        }

        return $this;
    }
}
