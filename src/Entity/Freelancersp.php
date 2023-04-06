<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\FreelancerspRepository;

#[ORM\Entity(repositoryClass: FreelancerspRepository::class)]
class Freelancersp
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
     * @var \Projets
     *
     * @ORM\ManyToOne(targetEntity="Projets")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idProjet", referencedColumnName="idProjet")
     * })
     */
    #[ORM\ManyToOne(targetEntity: Projets::class)]
    #[ORM\JoinColumn(name: 'idProjet', referencedColumnName: 'idProjet')]
    private ?Projets $idprojet = null;

    /*
    /**
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idUtilisateur", referencedColumnName="id")
     * })
     */

    #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
    #[ORM\JoinColumn(name: 'idUtilisateur', referencedColumnName: 'id')]
    private ?Utilisateur $idutilisateur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdprojet(): ?Projets
    {
        return $this->idprojet;
    }

    public function setIdprojet(?Projets $idprojet): self
    {
        $this->idprojet = $idprojet;

        return $this;
    }

    public function getIdutilisateur(): ?Utilisateur
    {
        return $this->idutilisateur;
    }

    public function setIdutilisateur(?Utilisateur $idutilisateur): self
    {
        $this->idutilisateur = $idutilisateur;

        return $this;
    }


}
