<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\NotBlank(message: 'Ce champ ne doit pas être vide')]
    #[Assert\Length(
        max: 255,
        maxMessage: 'La question est trop longue {{ value }},
        elle ne devrait pas dépasser {{ limit }} caractères',
    )]
    private string $content;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank(message: 'Ce champ ne doit pas être vide')]
    #[Assert\Range(
        min: 1,
        max: 5,
        notInRangeMessage: 'Veuillez choisir un niveau entre 1 et 5',
    )]
    private int $level;

    #[ORM\OneToMany(
        mappedBy: 'Question',
        targetEntity: Answer::class,
        orphanRemoval: true,
        cascade: ['persist', 'remove']
    )]
    #[Assert\Valid]
    private Collection $answers;

    #[ORM\OneToMany(mappedBy: 'question', targetEntity: QuestionAsked::class)]
    private Collection $questionAskeds;

    public function __construct()
    {
        $this->answers = new ArrayCollection();
        $this->questionAskeds = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }

    /**
     * @return Collection<int, Answer>
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(Answer $answer): self
    {
        if (!$this->answers->contains($answer)) {
            $this->answers[] = $answer;
            $answer->setQuestion($this);
        }

        return $this;
    }

    public function removeAnswer(Answer $answer): self
    {
        if ($this->answers->removeElement($answer)) {
            // set the owning side to null (unless already changed)
            if ($answer->getQuestion() === $this) {
                $answer->setQuestion(null);
            }
        }

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
            $questionAsked->setQuestion($this);
        }

        return $this;
    }

    public function removeQuestionAsked(QuestionAsked $questionAsked): self
    {
        if ($this->questionAskeds->removeElement($questionAsked)) {
            // set the owning side to null (unless already changed)
            if ($questionAsked->getQuestion() === $this) {
                $questionAsked->setQuestion(null);
            }
        }

        return $this;
    }
}
