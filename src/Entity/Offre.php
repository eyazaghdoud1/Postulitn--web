<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\OffreRepository;
use DateTime;

#[ORM\Entity(repositoryClass: OffreRepository::class)]
class Offre
{
    /*
    /**
     * @var int
     *
     * @ORM\Column(name="idOffre", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name:'idOffre', type:'integer', nullable:false)]
    private ?int $idoffre = null;

    /*
    /**
     * @var string
     *
     * @ORM\Column(name="poste", type="string", length=254, nullable=false)
     */
    #[ORM\Column(length:254, nullable:false)]
    private ?string $poste = null;

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
     * @var string
     *
     * @ORM\Column(name="lieu", type="string", length=254, nullable=false)
     */
    #[ORM\Column(length:254, nullable:false)]
    private ?string $lieu = null;

    /*
    /**
     * @var string
     *
     * @ORM\Column(name="entreprise", type="string", length=254, nullable=false)
     */
    #[ORM\Column(length:254, nullable:false)]
    private ?string $entreprise = null;

    /*
    /**
     * @var string
     *
     * @ORM\Column(name="specialite", type="string", length=254, nullable=false)
     */
    #[ORM\Column(length:254, nullable:false)]
    private ?string $specialite = null;

    /*
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateExpiration", type="date", nullable=false)
     */
    #[ORM\Column(nullable:false)]
    private ?DateTime $dateexpiration;

    /*
    /**
     * @var \Typeoffre
     *
     * @ORM\ManyToOne(targetEntity="Typeoffre")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idtype", referencedColumnName="idtype")
     * })
     */
    #[ORM\ManyToOne(targetEntity: Typeoffre::class)]
    #[ORM\JoinColumn(name: 'idtype', referencedColumnName: 'idtype')]
    private ?Typeoffre $idtype = null; 

    /*
    /**
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idrecruteur", referencedColumnName="id")
     * })
     */
    #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
    #[ORM\JoinColumn(name: 'idrecruteur', referencedColumnName: 'id')]
    private ?Utilisateur $idrecruteur = null;

    public function getIdoffre(): ?int
    {
        return $this->idoffre;
    }

    public function getPoste(): ?string
    {
        return $this->poste;
    }

    public function setPoste(string $poste): self
    {
        $this->poste = $poste;

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

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getEntreprise(): ?string
    {
        return $this->entreprise;
    }

    public function setEntreprise(string $entreprise): self
    {
        $this->entreprise = $entreprise;

        return $this;
    }

    public function getSpecialite(): ?string
    {
        return $this->specialite;
    }

    public function setSpecialite(string $specialite): self
    {
        $this->specialite = $specialite;

        return $this;
    }

    public function getDateexpiration(): ?\DateTimeInterface
    {
        return $this->dateexpiration;
    }

    public function setDateexpiration(\DateTimeInterface $dateexpiration): self
    {
        $this->dateexpiration = $dateexpiration;

        return $this;
    }

    public function getIdtype(): ?Typeoffre
    {
        return $this->idtype;
    }

    public function setIdtype(?Typeoffre $idtype): self
    {
        $this->idtype = $idtype;

        return $this;
    }

    public function getIdrecruteur(): ?Utilisateur
    {
        return $this->idrecruteur;
    }

    public function setIdrecruteur(?Utilisateur $idrecruteur): self
    {
        $this->idrecruteur = $idrecruteur;

        return $this;
    }


}
