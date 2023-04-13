<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\RoleRepository;

#[ORM\Entity(repositoryClass: RoleRepository::class)]
class Role
{
    /*
    /**
     * @var int
     *
     * @ORM\Column(name="idRole", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name:'idRole', type:'integer',nullable:false)]
    private ?int $idrole = null;

    /*
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=200, nullable=false)
     */
    #[ORM\Column(length:200, nullable:false)]
    private ?string $description = null;

    public function getIdrole(): ?int
    {
        return $this->idrole;
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
