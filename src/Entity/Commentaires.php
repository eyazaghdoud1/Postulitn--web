<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommentairesRepository;

#[ORM\Entity(repositoryClass: CommentairesRepository::class)]
class Commentaires
{
    /*
    /**
     * @var int
     *
     * @ORM\Column(name="idCommentaire", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name:'idCommentaire', type:'integer', nullable:false)]
    private ?int $idcommentaire = null;

    /*
    /**
     * @var string
     *
     * @ORM\Column(name="Contenu", type="string", length=254, nullable=false)
     */
    #[ORM\Column(length:254, nullable:false)]
    private ?string $contenu = null;

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
     *   @ORM\JoinColumn(name="idUser", referencedColumnName="id")
     * })
     */
    #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
    #[ORM\JoinColumn(name: 'idUser', referencedColumnName: 'id')]
    private ?Utilisateur $iduser = null;

    public function getIdcommentaire(): ?int
    {
        return $this->idcommentaire;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
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

    public function getIduser(): ?Utilisateur
    {
        return $this->iduser;
    }

    public function setIduser(?Utilisateur $iduser): self
    {
        $this->iduser = $iduser;

        return $this;
    }


}
