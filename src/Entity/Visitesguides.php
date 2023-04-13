<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\VisitesguidesRepository;
use DateTime;

#[ORM\Entity(repositoryClass: VisitesguidesRepository::class)]
class Visitesguides
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
     * @var \DateTime|null
     *
     * @ORM\Column(name="date", type="date", nullable=true)
     */
    #[ORM\Column(nullable:true)]
    private ?DateTime $date = null;

    /*
    /**
     * @var \Comptes
     *
     * @ORM\ManyToOne(targetEntity="Comptes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idCompte", referencedColumnName="idcompte")
     * })
     */
    
    #[ORM\ManyToOne(targetEntity: Comptes::class)]
    #[ORM\JoinColumn(name: 'idCompte', referencedColumnName: 'idcompte')]
    private ?Comptes $idcompte = null;

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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getIdcompte(): ?Comptes
    {
        return $this->idcompte;
    }

    public function setIdcompte(?Comptes $idcompte): self
    {
        $this->idcompte = $idcompte;

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
