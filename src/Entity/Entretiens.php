<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


use App\Repository\EntretiensRepository;
use DateTime;

#[ORM\Entity(repositoryClass: EntretiensRepository::class)]
class Entretiens
{
    /*
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name:'id', type:'integer', nullable:false)]
    private ?int $id = null;

    /*
    /**
     * @var string|null
     *
     * @ORM\Column(name="type", type="string", length=10, nullable=true)
     */
    #[ORM\Column(length:10, nullable:true)]
    private ?string $type = null;

    /*
    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date", type="date", nullable=true)
     */
    #[ORM\Column(nullable:true)]
    private ?DateTime $date = null ;

    /*
    /**
     * @var string
     *
     * @ORM\Column(name="heure", type="string", length=10, nullable=false)
     */
    #[ORM\Column(length:10, nullable:false)]
    private ?String $heure = null;

    /*
    /**
     * @var string
     *
     * @ORM\Column(name="lieu", type="string", length=50, nullable=false)
     */
    #[ORM\Column(length:50, nullable:false)]
    private ?string $lieu = null;

    /*
    /**
     * @var \Candidatures
     *
     * @ORM\ManyToOne(targetEntity="Candidatures")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idCandidature", referencedColumnName="id")
     * })
     */
    #[ORM\ManyToOne(targetEntity: Candidatures::class)]
    #[ORM\JoinColumn(name: 'idCandidature', referencedColumnName: 'id')]
    private ?Candidatures $idcandidature = null;

    /*
    /**
     * @var \Guidesentretiens
     *
     * @ORM\ManyToOne(targetEntity="Guidesentretiens")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idGuide", referencedColumnName="idGuide")
     * })
     */
    #[ORM\ManyToOne(targetEntity: Guidesentretiens::class)]
    #[ORM\JoinColumn(name: 'idGuide', referencedColumnName: 'idGuide')]
    private ?Guidesentretiens $idguide = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getHeure(): ?string
    {
        return $this->heure;
    }

    public function setHeure(string $heure): self
    {
        $this->heure = $heure;

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

    public function getIdcandidature(): ?Candidatures
    {
        return $this->idcandidature;
    }

    public function setIdcandidature(?Candidatures $idcandidature): self
    {
        $this->idcandidature = $idcandidature;

        return $this;
    }

    public function getIdguide(): ?Guidesentretiens
    {
        return $this->idguide;
    }

    public function setIdguide(?Guidesentretiens $idguide): self
    {
        $this->idguide = $idguide;

        return $this;
    }


}
