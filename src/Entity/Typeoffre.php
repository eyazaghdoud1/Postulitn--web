<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\TypeoffreRepository;

#[ORM\Entity(repositoryClass: TypeoffreRepository::class)]
class Typeoffre
{
    /*
    /**
     * @var int
     *
     * @ORM\Column(name="idtype", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name:'idtype', type:'integer',nullable:false)]
    private ?int $idtype = null;

    /*
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=254, nullable=false)
     */
    #[ORM\Column(length:254, nullable:false)]
    private ?string $description = null;

    public function getIdtype(): ?int
    {
        return $this->idtype;
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


}
