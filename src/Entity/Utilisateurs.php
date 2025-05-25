<?php


namespace App\Entity;

use App\Repository\UtilisateursRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UtilisateursRepository::class)]
class Utilisateurs implements UserInterface
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

    // UserInterface methods
    public function getRoles(): array
    {
        // Ensure we always return an array
        return [$this->role ?: 'ROLE_USER'];
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function getSalt(): ?string
    {
        // Not needed when using modern hashing algorithms (like bcrypt)
        return null;
    }

    public function getUsername(): string
    {
        return $this->email;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
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
