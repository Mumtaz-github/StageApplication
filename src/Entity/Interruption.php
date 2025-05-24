<?php

namespace App\Entity;

use App\Repository\InterruptionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InterruptionRepository::class)]
class Interruption
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $dateDebutInt = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $dateFinInt = null;

    #[ORM\ManyToOne(inversedBy: 'interruptions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Formation $formation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDebutInt(): ?\DateTime
    {
        return $this->dateDebutInt;
    }

    public function setDateDebutInt(\DateTime $dateDebutInt): static
    {
        $this->dateDebutInt = $dateDebutInt;

        return $this;
    }

    public function getDateFinInt(): ?\DateTime
    {
        return $this->dateFinInt;
    }

    public function setDateFinInt(\DateTime $dateFinInt): static
    {
        $this->dateFinInt = $dateFinInt;

        return $this;
    }

    public function getFormation(): ?Formation
    {
        return $this->formation;
    }

    public function setFormation(?Formation $formation): static
    {
        $this->formation = $formation;

        return $this;
    }
}
