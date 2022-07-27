<?php

namespace App\Entity;

use App\Repository\AnswerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AnswerRepository::class)]
class Answer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Question::class, inversedBy: 'answers')]
    #[ORM\JoinColumn(nullable: true)]
    #[Assert\NotBlank(message: 'Ce champ ne doit pas être vide')]
    private ?Question $question;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank(message: 'Ce champ ne doit pas être vide')]
    private string $content;

    #[ORM\Column(type: 'boolean')]
    private bool $isCorrect;

    #[ORM\OneToMany(mappedBy: 'answerQcm', targetEntity: QuestionAsked::class)]
    private Collection $questionAskeds;

    public function __construct()
    {
        $this->questionAskeds = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function isIsCorrect(): ?bool
    {
        return $this->isCorrect;
    }

    public function setIsCorrect(bool $isCorrect): self
    {
        $this->isCorrect = $isCorrect;

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
            $questionAsked->setAnswerQcm($this);
        }

        return $this;
    }

    public function removeQuestionAsked(QuestionAsked $questionAsked): self
    {
        if ($this->questionAskeds->removeElement($questionAsked)) {
            // set the owning side to null (unless already changed)
            if ($questionAsked->getAnswerQcm() === $this) {
                $questionAsked->setAnswerQcm(null);
            }
        }

        return $this;
    }
}
