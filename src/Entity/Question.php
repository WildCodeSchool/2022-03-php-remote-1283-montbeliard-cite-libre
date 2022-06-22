<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $content;

    #[ORM\Column(type: 'integer')]
    private int $level;

    #[ORM\OneToMany(mappedBy: 'question', targetEntity: Answer::class, orphanRemoval: true)]
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
