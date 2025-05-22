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
    private ?\DateTime $date_JourFerie = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $date_debut_JourFerie = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $date_finJourFerie = null;

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
        return $this->date_JourFerie;
    }

    public function setDateJourFerie(\DateTime $date_JourFerie): static
    {
        $this->date_JourFerie = $date_JourFerie;

        return $this;
    }

    public function getDateDebutJourFerie(): ?\DateTime
    {
        return $this->date_debut_JourFerie;
    }

    public function setDateDebutJourFerie(\DateTime $date_debut_JourFerie): static
    {
        $this->date_debut_JourFerie = $date_debut_JourFerie;

        return $this;
    }

    public function getDateFinJourFerie(): ?\DateTime
    {
        return $this->date_finJourFerie;
    }

    public function setDateFinJourFerie(\DateTime $date_finJourFerie): static
    {
        $this->date_finJourFerie = $date_finJourFerie;

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
