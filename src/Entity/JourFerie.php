<?php

namespace App\Entity;
use DateTimeInterface;

use App\Repository\JourFerieRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JourFerieRepository::class)]
class JourFerie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

#[ORM\Column(type: 'date')]
private ?\DateTimeInterface $date = null;

 #[ORM\Column(length: 4)]
    private ?string $annee = null;

    #[ORM\Column(length: 50)]
    private ?string $zone = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;
        return $this;
    }

    public function getAnnee(): ?string
    {
        return $this->annee;
    }

    public function setAnnee(string $annee): static
    {
        $this->annee = $annee;
        return $this;
    }

    public function getZone(): ?string
    {
        return $this->zone;
    }

    public function setZone(string $zone): static
    {
        $this->zone = $zone;
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













// namespace App\Entity;
// use DateTimeInterface;

// use App\Repository\JourFerieRepository;
// use Doctrine\ORM\Mapping as ORM;

// #[ORM\Entity(repositoryClass: JourFerieRepository::class)]
// class JourFerie
// {
//     #[ORM\Id]
//     #[ORM\GeneratedValue]
//     #[ORM\Column]
//     private ?int $id = null;

// #[ORM\Column(type: 'date')]
// private ?\DateTimeInterface $date = null;

//  #[ORM\Column(length: 4)]
//     private ?string $annee = null;

//     #[ORM\Column(length: 50)]
//     private ?string $zone = null;

//     #[ORM\Column(length: 255)]
//     private ?string $nom = null;

//     public function getId(): ?int
//     {
//         return $this->id;
//     }

//     public function getDate(): ?DateTimeInterface
//     {
//         return $this->date;
//     }

//     public function setDate(\DateTimeInterface $date): static
//     {
//         $this->date = $date;
//         return $this;
//     }

//     public function getAnnee(): ?string
//     {
//         return $this->annee;
//     }

//     public function setAnnee(string $annee): static
//     {
//         $this->annee = $annee;
//         return $this;
//     }

//     public function getZone(): ?string
//     {
//         return $this->zone;
//     }

//     public function setZone(string $zone): static
//     {
//         $this->zone = $zone;
//         return $this;
//     }

//     public function getNom(): ?string
//     {
//         return $this->nom;
//     }

//     public function setNom(string $nom): static
//     {
//         $this->nom = $nom;
//         return $this;
//     }
// }




















