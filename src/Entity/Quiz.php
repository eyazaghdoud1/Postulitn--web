<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\QuizRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: QuizRepository::class)]
class Quiz
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
    #[ORM\Column(name:'id', type:'integer',nullable:false)]
    private ?int $id = null;

    /*
    /**
     * @var string
     *
     * @ORM\Column(name="secteur", type="string", length=100, nullable=false)
     */

    #[ORM\Column(length:100, nullable:false)]
    #[Assert\NotBlank(message:"Vous devez indiquer le secteur du quiz.")]
    private ?string $secteur = null;

    /*
    /**
     * @var string
     *
     * @ORM\Column(name="specialite", type="string", length=100, nullable=false)
     */
    #[ORM\Column(length:100, nullable:false)]
    #[Assert\NotBlank(message:"Vous devez indiquer la spécialité du quiz.")]
    private ?string $specialite = null;

    /*
    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=20, nullable=false)
     */
    #[ORM\Column(length:20, nullable:false)]
    #[Assert\NotBlank(message:"Vous devez indiquer le nom du secteur.")]
    private ?string $nom = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSecteur(): ?string
    {
        return $this->secteur;
    }

    public function setSecteur(string $secteur): self
    {
        $this->secteur = $secteur;

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

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }


}
