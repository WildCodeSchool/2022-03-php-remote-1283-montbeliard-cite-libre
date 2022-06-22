<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

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

    #[ORM\Column(type: 'time', nullable: true)]
    private ?\DateTimeInterface $duration;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $score;

    #[ORM\Column(type: 'string', length: 45, nullable: true)]
    private ?string $type;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'games')]
    private ?User $user;

    #[ORM\OneToMany(mappedBy: 'Game', targetEntity: QuestionAsked::class)]
    private Collection $questionAskeds;

    public function __construct()
    {
        $this->questionAskeds = new ArrayCollection();
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

    public function getDuration(): ?\DateTimeInterface
    {
        return $this->duration;
    }

    public function setDuration(?\DateTimeInterface $duration): self
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
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
}
