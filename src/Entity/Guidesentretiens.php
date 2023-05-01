<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\GuidesentretiensRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;



#[ORM\Entity(repositoryClass: GuidesentretiensRepository::class)]
class Guidesentretiens
{
    /*
    /**
     * @var int
     *
     * @ORM\Column(name="idGuide", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name:'idGuide', type:'integer', nullable:false)]
    private ?int $idguide = null;

    /*
    /**
     * @var string
     *
     * @ORM\Column(name="domaine", type="string", length=30, nullable=false)
     *  @Assert\NotBlank
     * @Assert\Regex(
     *     pattern="/^[^0-9]*$/",
     *     message="Le champ ne doit pas contenir de chiffres"
     * )
     */
    #[ORM\Column(length:30, nullable:false)]
    private ?string $domaine = null;

    /*
    /**
     * @var string
     *
     * @ORM\Column(name="specialite", type="string", length=30, nullable=false)
     *  @Assert\NotBlank
     */
    #[ORM\Column(length:30, nullable:false)]
    private ?string $specialite = null;

   /*
   /**  
    * @var string
    *
    * @ORM\Column(name="support", type="string", length=200, nullable=false)
    *  @Assert\NotBlank
    */
   #[ORM\Column(length:200, nullable:false)]
   private ?string $support = null;

        
    /*
    /**
     * @var float
     *
     * @ORM\Column(name="note", type="float", precision=10, scale=0, nullable=false)
     */
    #[ORM\Column(nullable:false)]
    private float $note = 0.0;

    /*
    /**
     * @var int
     *
     * @ORM\Column(name="nombreNotes", type="integer", nullable=false)
     */
    #[ORM\Column(nullable:false)]
    private int $nombrenotes = 0;

    public function getIdguide(): ?int
    {
        return $this->idguide;
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

    public function getSpecialite(): ?string
    {
        return $this->specialite;
    }

    public function setSpecialite(string $specialite): self
    {
        $this->specialite = $specialite;

        return $this;
    }

   

    public function getNote(): ?float
    {
        return $this->note;
    }

    public function setNote(float $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getNombrenotes(): ?int
    {
        return $this->nombrenotes;
    }

    public function setNombrenotes(int $nombrenotes): self
    {
        $this->nombrenotes = $nombrenotes;

        return $this;
    }

    public function getSupport(): ?string
    {
        return $this->support;
    }

    public function setSupport(?string $support): void
    {
        $this->support = $support;
    }
   

}