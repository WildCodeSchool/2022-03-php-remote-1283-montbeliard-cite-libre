<?php

namespace App\Entity;

use App\Repository\CardRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CardRepository::class)]
class Card
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 100)]
    private string $name;

    #[ORM\Column(type: 'text')]
    private string $description;

    #[ORM\Column(type: 'string', length: 255)]
    private string $image;

    #[ORM\Column(type: 'integer', nullable: true)]
    private int $credit;

    #[ORM\Column(type: 'json', nullable: true)]
    private array $rule = [];

    #[ORM\ManyToOne(targetEntity: Family::class, inversedBy: 'cards', fetch: "EAGER")]
    private ?Family $family;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'cards', fetch: "EAGER")]
    private ?Category $category;

    #[ORM\OneToMany(mappedBy: 'card', targetEntity: CardWon::class, fetch: "EAGER")]
    private Collection $cardWons;

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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getCredit(): ?int
    {
        return $this->credit;
    }

    public function setCredit(?int $credit): self
    {
        $this->credit = $credit;

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

    public function getFamily(): ?Family
    {
        return $this->family;
    }

    public function setFamily(?Family $family): self
    {
        $this->family = $family;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

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
            $cardWon->setCard($this);
        }

        return $this;
    }

    public function removeCardWon(CardWon $cardWon): self
    {
        if ($this->cardWons->removeElement($cardWon)) {
            // set the owning side to null (unless already changed)
            if ($cardWon->getCard() === $this) {
                $cardWon->setCard(null);
            }
        }

        return $this;
    }
}
