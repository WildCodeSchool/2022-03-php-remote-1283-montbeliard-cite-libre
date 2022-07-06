<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $name;

    #[ORM\Column(type: 'datetime_immutable')]
    private ?\DateTimeImmutable $startedAt;

    #[ORM\Column(type: 'datetime_immutable')]
    private ?\DateTimeImmutable $endedAt;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $duration;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $score;

    #[ORM\Column(type: 'string', length: 45, nullable: true)]
    private ?string $type;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'games')]
    private ?UserInterface $user;

    #[ORM\OneToMany(mappedBy: 'game', targetEntity: QuestionAsked::class)]
    private Collection $questionAskeds;

    #[ORM\OneToMany(mappedBy: 'game', targetEntity: CardWon::class)]
    private Collection $cardWons;


    #[ORM\Column(type: 'integer', nullable: true)]
    private int $turn;

    public function __construct()
    {
        $this->questionAskeds = new ArrayCollection();
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

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getStartedAt(): ?\DateTimeImmutable
    {
        return $this->startedAt;
    }

    public function setStartedAt(\DateTimeImmutable $startedAt): self
    {
        $this->startedAt = $startedAt;

        return $this;
    }

    public function getEndedAt(): ?\DateTimeImmutable
    {
        return $this->endedAt;
    }

    public function setEndedAt(\DateTimeImmutable $endedAt): self
    {
        $this->endedAt = $endedAt;

        return $this;
    }

    public function getDuration(): ?string
    {
        return $this->duration;
    }

    public function setDuration(?string $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(?int $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    public function setUser(?UserInterface $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, QuestionAsked>
     */
    public function getQuestionAskeds(): Collection
    {
        return $this->questionAskeds;
    }

    public function addQuestionAsked(QuestionAsked $questionAsked): self
    {
        if (!$this->questionAskeds->contains($questionAsked)) {
            $this->questionAskeds[] = $questionAsked;
            $questionAsked->setGame($this);
        }

        return $this;
    }

    public function removeQuestionAsked(QuestionAsked $questionAsked): self
    {
        if ($this->questionAskeds->removeElement($questionAsked)) {
            // set the owning side to null (unless already changed)
            if ($questionAsked->getGame() === $this) {
                $questionAsked->setGame(null);
            }
        }

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
            $cardWon->setGame($this);
        }

        return $this;
    }

    public function removeCardWon(CardWon $cardWon): self
    {
        if ($this->cardWons->removeElement($cardWon)) {
            // set the owning side to null (unless already changed)
            if ($cardWon->getGame() === $this) {
                $cardWon->setGame(null);
            }
        }

        return $this;
    }
    public function getTurn(): ?int
    {
        return $this->turn;
    }

    public function setTurn(?int $turn): self
    {
        $this->turn = $turn;

        return $this;
    }
}
