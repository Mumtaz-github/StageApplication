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
    private ?\DateTime $date_debut_int = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $date_fin_int = null;

    #[ORM\ManyToOne(inversedBy: 'interruptions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Formation $formation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDebutInt(): ?\DateTime
    {
        return $this->date_debut_int;
    }

    public function setDateDebutInt(\DateTime $date_debut_int): static
    {
        $this->date_debut_int = $date_debut_int;

        return $this;
    }

    public function getDateFinInt(): ?\DateTime
    {
        return $this->date_fin_int;
    }

    public function setDateFinInt(\DateTime $date_fin_int): static
    {
        $this->date_fin_int = $date_fin_int;

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
