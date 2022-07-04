<?php

namespace App\Entity;

use App\Repository\CardApocalypseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CardApocalypseRepository::class)]
class CardApocalypse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    private $description;

    #[ORM\Column(type: 'json', nullable: true)]
    private $rule = [];

    #[ORM\OneToMany(mappedBy: 'cardApocalypse', targetEntity: CardWon::class)]
    private $cardWons;

    #[ORM\Column(type: 'string', length: 255)]
    private $type;

    #[ORM\Column(type: 'string', length: 255)]
    private $image;

    public function __construct()
    {
        $this->cardWons = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getRule(): ?array
    {
        return $this->rule;
    }

    public function setRule(?array $rule): self
    {
        $this->rule = $rule;

        return $this;
    }

    /**
     * @return Collection<int, CardWon>
     */
    public function getCardWons(): Collection
    {
        return $this->cardWons;
    }

    public function addCardWon(CardWon $cardWon): self
    {
        if (!$this->cardWons->contains($cardWon)) {
            $this->cardWons[] = $cardWon;
            $cardWon->setCardApocalypse($this);
        }

        return $this;
    }

    public function removeCardWon(CardWon $cardWon): self
    {
        if ($this->cardWons->removeElement($cardWon)) {
            // set the owning side to null (unless already changed)
            if ($cardWon->getCardApocalypse() === $this) {
                $cardWon->setCardApocalypse(null);
            }
        }

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }
}
