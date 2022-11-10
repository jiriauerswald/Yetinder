<?php

namespace App\Entity;

use App\Repository\YetiRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: YetiRepository::class)]
class Yeti
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Assert\NotBlank]
    private ?int $age = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Assert\NotBlank]
    private ?int $height = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $gender = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $interest = null;

    #[ORM\Column]
    private ?int $score = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $nickname): self
    {
        $this->name = $nickname;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(int $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $sex): self
    {
        $this->gender = $sex;

        return $this;
    }

    public function getInterest(): ?string
    {
        return $this->interest;
    }

    public function setInterest(string $interest): self
    {
        $this->interest = $interest;

        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): self
    {
        $this->score = $score;

        return $this;
    }
}
