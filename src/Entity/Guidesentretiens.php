<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\GuidesentretiensRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;



#[ORM\Entity(repositoryClass: GuidesentretiensRepository::class)]
#[Vich\Uploadable]
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

    

    /**
     * @ORM\Column(type="string", length=255)
     * @var string|null
     */
    
    private $filename;



    /**
     * @Vich\UploadableField(mapping="Guidesentretiens_files", fileNameProperty="filename")
     * @Assert\File(
     *     maxSize="5M",
     *     mimeTypes={"image/png", "image/jpeg", "image/gif"}
     * )
     * @var File|null
     */
    private $file;



    #[ORM\Column(length: 255,nullable:true)]
    private $support;

    /**
     * 
     */
    private $supportFile;
   

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(?string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(?File $file = null): self
    {
        $this->file = $file;

        return $this;
    }
    
    
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

    

    public function getSupportFile(): ?File
    {
        return $this->supportFile;
    }
    public function setSupportFile(File $supportFile = null): void
    {
        $this->supportFile = $supportFile;
        if ($supportFile) {
            
        }
    }

    public function getSupport(): ?string
    {
        return $this->support;
    }

    public function setSupport(?string $support): void
    {
        $this->support = $support;
    }
    public function getImageUrl(): ?string
    {
        return '/uploads/Guidesentretiens/' . $this->support;
    }
   

}