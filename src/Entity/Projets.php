<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProjetsRepository;
use DateTime;

#[ORM\Entity(repositoryClass: ProjetsRepository::class)]
class Projets
{
    /*
    /**
     * @var int
     *
     * @ORM\Column(name="idProjet", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name:'idProjet', type:'integer', nullable:false)]
    private ?int $idprojet = null;

    /*
    /**
     * @var string
     *
     * @ORM\Column(name="theme", type="string", length=254, nullable=false)
     */
    #[ORM\Column(length:254,nullable:false)]
    private ?string $theme = null;

    /*
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=254, nullable=false)
     */
    #[ORM\Column(length:254, nullable:false)]
    private ?string $description = null;

    /*
    /**
     * @var int
     *
     * @ORM\Column(name="duree", type="integer", nullable=false)
     */
    #[ORM\Column(nullable:false)]
    private ?int $duree = null;

    /*
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateDebut", type="date", nullable=false)
     */
    #[ORM\Column(nullable:false)]
    private ?DateTime $datedebut;

    /*
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateFin", type="date", nullable=false)
     */
    #[ORM\Column(nullable:false)]
    private ?DateTime $datefin;

    /*
    /**
     * @var string
     *
     * @ORM\Column(name="Nom", type="string", length=254, nullable=false)
     */
    #[ORM\Column(length:254, nullable:false)]
    private ?string $nom = null;

    /*
    /**
     * @var int
     *
     * @ORM\Column(name="note", type="integer", nullable=false)
     */
    #[ORM\Column(nullable:false)]
    private ?int $note = null;

    /*
    /**
     * @var \Secteurs
     *
     * @ORM\ManyToOne(targetEntity="Secteurs")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idSecteur", referencedColumnName="idSecteur")
     * })
     */
    #[ORM\ManyToOne(targetEntity: Secteurs::class)]
    #[ORM\JoinColumn(name: 'idSecteur', referencedColumnName: 'idSecteur')]
    private ?Secteurs $idsecteur = null;

    /*
    /**
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idResponsable", referencedColumnName="id")
     * })
     */
    #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
    #[ORM\JoinColumn(name: 'idResponsable', referencedColumnName: 'id')]
    private ?Utilisateur $idresponsable = null;

    public function getIdprojet(): ?int
    {
        return $this->idprojet;
    }

    public function getTheme(): ?string
    {
        return $this->theme;
    }

    public function setTheme(string $theme): self
    {
        $this->theme = $theme;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(int $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getDatedebut(): ?\DateTimeInterface
    {
        return $this->datedebut;
    }

    public function setDatedebut(\DateTimeInterface $datedebut): self
    {
        $this->datedebut = $datedebut;

        return $this;
    }

    public function getDatefin(): ?\DateTimeInterface
    {
        return $this->datefin;
    }

    public function setDatefin(\DateTimeInterface $datefin): self
    {
        $this->datefin = $datefin;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(int $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getIdsecteur(): ?Secteurs
    {
        return $this->idsecteur;
    }

    public function setIdsecteur(?Secteurs $idsecteur): self
    {
        $this->idsecteur = $idsecteur;

        return $this;
    }

    public function getIdresponsable(): ?Utilisateur
    {
        return $this->idresponsable;
    }

    public function setIdresponsable(?Utilisateur $idresponsable): self
    {
        $this->idresponsable = $idresponsable;

        return $this;
    }


}
