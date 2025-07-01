<?php


namespace App\Entity\Trait;

use Doctrine\ORM\Mapping as ORM;

trait CreatedAtTrait
{
    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeInterface $createdAt;

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }
}
// namespace App\Entity\Trait;

// use Doctrine\ORM\Mapping as ORM;
// use App\Repository\FormationRepository;

// #[ORM\Entity(repositoryClass: FormationRepository::class)]
// class Formation
// {
//     // Add this right after your ID field
//     #[ORM\Column(type: 'datetime')]
//     private \DateTimeInterface $createdAt;

//     public function __construct()
//     {
//         $this->createdAt = new \DateTimeImmutable(); // Auto-set on creation
//         // ... rest of your constructor ...
//     }

//     // Add getter
//     public function getCreatedAt(): \DateTimeInterface
//     {
//         return $this->createdAt;
//     }
// }