<?php

namespace App\Entity;

use App\Repository\UtilisateursRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UtilisateursRepository::class)]
class Utilisateurs implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $role = null;

    #[ORM\Column]
    private ?\DateTime $dateInvitation = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;
        return $this;
    }

    public function getDateInvitation(): ?\DateTime
    {
        return $this->dateInvitation;
    }

    public function setDateInvitation(\DateTime $dateInvitation): static
    {
        $this->dateInvitation = $dateInvitation;
        return $this;
    }

    // Needed for PasswordAuthenticatedUserInterface
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function getRoles(): array
    {
        // Wrap the string role into an array for Symfony
        return [$this->role ?: 'ROLE_USER'];
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getSalt(): ?string
    {
        // No salt needed with modern algorithms (bcrypt, sodium)
        return null;
    }

    public function eraseCredentials(): void
    {
        // Clear sensitive data if needed
    }
}



















// namespace App\Entity;

// use App\Repository\UtilisateursRepository;
// use Doctrine\ORM\Mapping as ORM;

// #[ORM\Entity(repositoryClass: UtilisateursRepository::class)]
// class Utilisateurs
// {
//     #[ORM\Id]
//     #[ORM\GeneratedValue]
//     #[ORM\Column]
//     private ?int $id = null;

//     #[ORM\Column(length: 255)]
//     private ?string $nom = null;

//     #[ORM\Column(length: 255)]
//     private ?string $prenom = null;

//     #[ORM\Column(length: 255)]
//     private ?string $email = null;

//     #[ORM\Column(length: 255)]
//     private ?string $role = null;

//     #[ORM\Column]
//     private ?\DateTime $dateInvitation = null;

//     public function getId(): ?int
//     {
//         return $this->id;
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

//     public function getPrenom(): ?string
//     {
//         return $this->prenom;
//     }

//     public function setPrenom(string $prenom): static
//     {
//         $this->prenom = $prenom;

//         return $this;
//     }

//     public function getEmail(): ?string
//     {
//         return $this->email;
//     }

//     public function setEmail(string $email): static
//     {
//         $this->email = $email;

//         return $this;
//     }

//     public function getRole(): ?string
//     {
//         return $this->role;
//     }

//     public function setRole(string $role): static
//     {
//         $this->role = $role;

//         return $this;
//     }

//     public function getDateInvitation(): ?\DateTime
//     {
//         return $this->dateInvitation;
//     }

//     public function setDateInvitation(\DateTime $dateInvitation): static
//     {
//         $this->dateInvitation = $dateInvitation;

//         return $this;
//     }
// }
