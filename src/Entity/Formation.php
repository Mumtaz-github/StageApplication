<?php

namespace App\Entity;

use App\Repository\FormationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FormationRepository::class)]
class Formation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $actif_formation = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $numero = null;

    #[ORM\Column(length: 255)]
    private ?string $etat = null;

    #[ORM\Column(length: 255)]
    private ?string $titre_Professionnel = null;

    #[ORM\Column]
    private ?int $niveau = null;

    #[ORM\Column(length: 255)]
    private ?string $nb_Stagiaires_previsionnel = null;

    #[ORM\Column(length: 255)]
    private ?string $groupe_rattachement = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $date_debut = null;

    #[ORM\ManyToOne(inversedBy: 'formations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Formateur $formateur = null;

    /**
     * @var Collection<int, Interruption>
     */
    #[ORM\OneToMany(targetEntity: Interruption::class, mappedBy: 'formation')]
    private Collection $interruptions;

    /**
     * @var Collection<int, PeriodEnEntreprise>
     */
    #[ORM\OneToMany(targetEntity: PeriodEnEntreprise::class, mappedBy: 'formation')]
    private Collection $periodEnEntreprises;

    public function __construct()
    {
        $this->interruptions = new ArrayCollection();
        $this->periodEnEntreprises = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isActifFormation(): ?bool
    {
        return $this->actif_formation;
    }

    public function setActifFormation(bool $actif_formation): static
    {
        $this->actif_formation = $actif_formation;

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

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): static
    {
        $this->numero = $numero;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): static
    {
        $this->etat = $etat;

        return $this;
    }

    public function getTitreProfessionnel(): ?string
    {
        return $this->titre_Professionnel;
    }

    public function setTitreProfessionnel(string $titre_Professionnel): static
    {
        $this->titre_Professionnel = $titre_Professionnel;

        return $this;
    }

    public function getNiveau(): ?int
    {
        return $this->niveau;
    }

    public function setNiveau(int $niveau): static
    {
        $this->niveau = $niveau;

        return $this;
    }

    public function getNbStagiairesPrevisionnel(): ?string
    {
        return $this->nb_Stagiaires_previsionnel;
    }

    public function setNbStagiairesPrevisionnel(string $nb_Stagiaires_previsionnel): static
    {
        $this->nb_Stagiaires_previsionnel = $nb_Stagiaires_previsionnel;

        return $this;
    }

    public function getGroupeRattachement(): ?string
    {
        return $this->groupe_rattachement;
    }

    public function setGroupeRattachement(string $groupe_rattachement): static
    {
        $this->groupe_rattachement = $groupe_rattachement;

        return $this;
    }

    public function getDateDebut(): ?\DateTime
    {
        return $this->date_debut;
    }

    public function setDateDebut(\DateTime $date_debut): static
    {
        $this->date_debut = $date_debut;

        return $this;
    }

    public function getFormateur(): ?Formateur
    {
        return $this->formateur;
    }

    public function setFormateur(?Formateur $formateur): static
    {
        $this->formateur = $formateur;

        return $this;
    }

    /**
     * @return Collection<int, Interruption>
     */
    public function getInterruptions(): Collection
    {
        return $this->interruptions;
    }

    public function addInterruption(Interruption $interruption): static
    {
        if (!$this->interruptions->contains($interruption)) {
            $this->interruptions->add($interruption);
            $interruption->setFormation($this);
        }

        return $this;
    }

    public function removeInterruption(Interruption $interruption): static
    {
        if ($this->interruptions->removeElement($interruption)) {
            // set the owning side to null (unless already changed)
            if ($interruption->getFormation() === $this) {
                $interruption->setFormation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PeriodEnEntreprise>
     */
    public function getPeriodEnEntreprises(): Collection
    {
        return $this->periodEnEntreprises;
    }

    public function addPeriodEnEntreprise(PeriodEnEntreprise $periodEnEntreprise): static
    {
        if (!$this->periodEnEntreprises->contains($periodEnEntreprise)) {
            $this->periodEnEntreprises->add($periodEnEntreprise);
            $periodEnEntreprise->setFormation($this);
        }

        return $this;
    }

    public function removePeriodEnEntreprise(PeriodEnEntreprise $periodEnEntreprise): static
    {
        if ($this->periodEnEntreprises->removeElement($periodEnEntreprise)) {
            // set the owning side to null (unless already changed)
            if ($periodEnEntreprise->getFormation() === $this) {
                $periodEnEntreprise->setFormation(null);
            }
        }

        return $this;
    }
}
