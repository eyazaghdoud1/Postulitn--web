<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UtilisateurRepository;
use DateTime;
use phpDocumentor\Reflection\Types\Self_;
use PhpParser\Node\Expr\Cast\String_;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
#use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
/**
 * @ORM\Entity
 * @UniqueEntity(fields={"email"}, message="email déjà utilisé !")
 */
class Utilisateur implements UserInterface
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
    #[ORM\Column(name: 'id', type: 'integer', nullable: false)]
    private ?int $id = null;

    /*
    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=30, nullable=false)
     */
    #[ORM\Column(length: 30, nullable: false)]
    #[Assert\NotBlank(message: "Il faut insérer un nom")]
    private ?string $nom = null;

    /*
    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=30, nullable=false)
     */
    #[ORM\Column(length: 30, nullable: false)]
    #[Assert\NotBlank(message: "Il faut insérer un prénom")]
    private ?string $prenom = null;

    /*
    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=50, nullable=false)
     */
    #[ORM\Column(length: 50, nullable: false)]
    #[Assert\NotBlank(message: "Il faut insérer un email")]
    #[Assert\Email(message: "L'email '{{ value }}' n'est pas valide ")]
    private ?string $email = null;

    /*
    /**
     * @var string
     *
     * @ORM\Column(name="tel", type="string", length=50, nullable=false)
     */
    #[ORM\Column(length: 50, nullable: false)]
    #[Assert\NotBlank(message: "Il faut insérer un numéro de téléphone")]
    #[Assert\Length(min: 8, minMessage: "Le numéro de téléphone doit contenir 8 chiffres")]
    #[Assert\Length(max: 8, maxMessage: "Le numéro de téléphone doit contenir 8 chiffres")]
    private ?string $tel = null;

    /*
    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=50, nullable=false)
     */
    #[ORM\Column(length: 50, nullable: false)]
    #[Assert\NotBlank(message: "Il faut insérer une adresse")]
    private ?string $adresse = null;

    /*
    /**
     * @var string
     *
     * @ORM\Column(name="mdp", type="string", length=100, nullable=false)
     */
    #[ORM\Column(length: 100, nullable: false)]
    #[Assert\NotBlank(message: "Il faut insérer un mot de passe ! ")]
    private ?string $mdp = null;

    /*
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateNaissance", type="date", nullable=false)
     */
    #[ORM\Column(nullable: false)]
    //#[Assert\DateTime(message: "Il faut insérer une date de naissance")]
    private ?DateTime $datenaissance = null;

    /*
    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=1000, nullable=false)
     */
    #[ORM\Column(length: 1000, nullable: false)]
    private ?string $salt = null;


    /*
    /**
     * @var \Role
     *
     * @ORM\ManyToOne(targetEntity="Role")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idRole", referencedColumnName="idRole")
     * })
     */
    #[ORM\ManyToOne(targetEntity: Role::class)]
    #[ORM\JoinColumn(name: 'idRole', referencedColumnName: 'idRole')]
    #[Assert\NotBlank(message: "Il faut insérer un rôle")]
    private ?Role $idrole = null;

    /*
    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=20, nullable=false)
     */
    #[ORM\Column(length: 20, nullable: false)]
    private ?string $code = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(string $tel): self
    {
        $this->tel = $tel;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getMdp(): ?string
    {
        return $this->mdp;
    }

    public function setMdp(string $mdp): self
    {
        $this->mdp = $mdp;

        return $this;
    }

    public function getDatenaissance(): ?\DateTimeInterface
    {
        return $this->datenaissance;
    }

    public function setDatenaissance(\DateTimeInterface $datenaissance): self
    {
        $this->datenaissance = $datenaissance;

        return $this;
    }


    public function setSalt(string $salt): self
    {
        $this->salt = $salt;

        return $this;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;
        return $this;
    }


    public function getCode(): ?string
    {
        return $this->code;
    }

    public function getIdrole(): ?Role
    {
        return $this->idrole;
    }

    public function setIdrole(?Role $idrole): self
    {
        $this->idrole = $idrole;

        return $this;
    }
    public function getRoles(): ?string
    {
        return $this->idrole->getDescription();
    }

    public function getPassword(): ?string
    {
        return $this->mdp;
    }

    public function getSalt(): ?string
    {
        return $this->salt;
    }

    public function getUsername(): ?string
    {
        return $this->nom;
    }

    public function getUserIdentifier(): ?string
    {
        return $this->email;
    }

    public function eraseCredentials()
    {
    }
}
