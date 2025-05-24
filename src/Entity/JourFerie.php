<?php

namespace App\Entity;

use App\Repository\JourFerieRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JourFerieRepository::class)]
class JourFerie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $date = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $dateJourFerie = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $dateDebutJourFerie = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $dateFinJourFerie = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(string $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getDateJourFerie(): ?\DateTime
    {
        return $this->dateJourFerie;
    }

    public function setDateJourFerie(\DateTime $date_JourFerie): static
    {
        $this->dateJourFerie = $date_JourFerie;

        return $this;
    }

    public function getDateDebutJourFerie(): ?\DateTime
    {
        return $this->dateDebutJourFerie;
    }

    public function setDateDebutJourFerie(\DateTime $dateDebutJourFerie): static
    {
        $this->dateDebutJourFerie = $dateDebutJourFerie;

        return $this;
    }

    public function getDateFinJourFerie(): ?\DateTime
    {
        return $this->dateFinJourFerie;
    }

    public function setDateFinJourFerie(\DateTime $dateFinJourFerie): static
    {
        $this->dateFinJourFerie = $dateFinJourFerie;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }
}
