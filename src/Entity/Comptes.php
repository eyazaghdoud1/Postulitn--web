<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ComptesRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: ComptesRepository::class)]
class Comptes
{
    /*
    /**
     * @var int
     *
     * @ORM\Column(name="idcompte", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name:'idcompte', type:'integer',nullable:false)]
    #[Groups(["comptes"])]
    private ?int $idcompte = null;

    /*
    /**
     * @var string|null
     *
     * @ORM\Column(name="photo", type="string", length=200, nullable=true)
     * @Assert\NotBlank
     */
    
    #[ORM\Column(length:200, nullable:true)]
    #[Groups(["comptes"])]
    private ?string $photo = null;

    /*
    /**
     * @var string|null
     *
     * @ORM\Column(name="diplome", type="string", length=50, nullable=true)
     * @Assert\NotBlank
     */
    #[ORM\Column(length:50, nullable:true)]
    #[Groups(["comptes"])]
    private ?string $diplome = null;

    /*
    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="dateDiplome", type="date", nullable=true)
     * @Assert\NotBlank
     */
    #[ORM\Column(nullable:true)]
    #[Groups(["comptes"])]
    private ?DateTime $datediplome = null;

    /*
    /**
     * @var string|null
     *
     * @ORM\Column(name="entreprise", type="string", length=50, nullable=true)
     * @Assert\NotBlank
     */
    #[ORM\Column(length:50, nullable:true)]
    #[Groups(["comptes"])]
    private ?string $entreprise = null;

    /*
    /**
     * @var string|null
     *
     * @ORM\Column(name="experience", type="string", length=50, nullable=true)
     * @Assert\NotBlank
     */
    #[ORM\Column(length:50, nullable:true)]
    #[Groups(["comptes"])]
    private ?string $experience = null;

    /*
    /**
     * @var string
     *
     * @ORM\Column(name="domaine", type="string", length=200, nullable=false)
     * @Assert\NotBlank
     */
    #[ORM\Column(length:200, nullable:false)]
    #[Groups(["comptes"])]
    private ?string $domaine = null;

    /*
    /**
     * @var string|null
     *
     * @ORM\Column(name="poste", type="string", length=200, nullable=true)
     * @Assert\NotBlank
     */
    #[ORM\Column(length:200, nullable:true)]
    #[Groups(["comptes"])]
    private ?string $poste = null;

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
    #[Groups(["comptes"])]
    private ?Utilisateur $idutilisateur = null;

    public function getIdcompte(): ?int
    {
        return $this->idcompte;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getDiplome(): ?string
    {
        return $this->diplome;
    }

    public function setDiplome(?string $diplome): self
    {
        $this->diplome = $diplome;

        return $this;
    }

    public function getDatediplome(): ?\DateTimeInterface
    {
        return $this->datediplome;
    }

    public function setDatediplome(?\DateTimeInterface $datediplome): self
    {
        $this->datediplome = $datediplome;

        return $this;
    }

    public function getEntreprise(): ?string
    {
        return $this->entreprise;
    }

    public function setEntreprise(?string $entreprise): self
    {
        $this->entreprise = $entreprise;

        return $this;
    }

    public function getExperience(): ?string
    {
        return $this->experience;
    }

    public function setExperience(?string $experience): self
    {
        $this->experience = $experience;

        return $this;
    }

    public function getDomaine(): ?string
    {
        return $this->domaine;
    }

    public function setDomaine(string $domaine): self
    {
        $this->domaine = $domaine;

        return $this;
    }

    public function getPoste(): ?string
    {
        return $this->poste;
    }

    public function setPoste(?string $poste): self
    {
        $this->poste = $poste;

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
