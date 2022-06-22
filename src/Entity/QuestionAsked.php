<?php

namespace App\Entity;

use App\Repository\QuestionAskedRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestionAskedRepository::class)]
class QuestionAsked
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\ManyToOne(targetEntity: Question::class, inversedBy: 'questionAskeds')]
    private ?Question $question;

    #[ORM\ManyToOne(targetEntity: Game::class, inversedBy: 'questionAskeds')]
    private ?Game $Game;

    public function getId(): ?int
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

    public function getGame(): ?Game
    {
        return $this->Game;
    }

    public function setGame(?Game $Game): self
    {
        $this->Game = $Game;

        return $this;
    }
}
