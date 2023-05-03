<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\SecteursRepository;
use Symfony\Component\Form\FormTypeInterface;
use App\Form\SecteursType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity(repositoryClass: SecteursRepository::class)]
class Secteurs
{
    /*
    /**
     * @var int
     *
     * @ORM\Column(name="idSecteur", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name:'idSecteur', type:'integer', nullable:false)]
    private ?int $idsecteur = null;

    /*
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=254, nullable=false)
     *
     */
    #[ORM\Column(length:254, nullable:false)]
    #[Assert\NotBlank(message:"Vous devez insérer le nom du nouveau secteur.")]
    /*#[Assert\NotBlank(message:"Le secteur existe deja !")]*/
    private ?string $description = null;

    public function getIdsecteur(): ?int
    {
        return $this->idsecteur;
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
