<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\QuizscoresRepository;
use DateTime;

#[ORM\Entity(repositoryClass: QuizscoresRepository::class)]
class Quizscores
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
     * @var int
     *
     * @ORM\Column(name="score", type="integer", nullable=false)
     */
    #[ORM\Column(nullable:false)]
    private ?int $score = null;

    /*
    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date", type="date", nullable=true)
     */
    #[ORM\Column(nullable:true)]
    private ?DateTime $date = null;

    /*
    /**
     * @var \Quiz
     *
     * @ORM\ManyToOne(targetEntity="Quiz")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idQuiz", referencedColumnName="id")
     * })
     */
    #[ORM\ManyToOne(targetEntity: Quiz::class)]
    #[ORM\JoinColumn(name: 'idQuiz', referencedColumnName: 'id')]
    private ?Quiz $idquiz = null;

    /*
    /**
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idCandidat", referencedColumnName="id")
     * })
     */
    #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
    #[ORM\JoinColumn(name: 'idCandidat', referencedColumnName: 'id')]
    private ?Utilisateur $idcandidat = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getIdquiz(): ?Quiz
    {
        return $this->idquiz;
    }

    public function setIdquiz(?Quiz $idquiz): self
    {
        $this->idquiz = $idquiz;

        return $this;
    }

    public function getIdcandidat(): ?Utilisateur
    {
        return $this->idcandidat;
    }

    public function setIdcandidat(?Utilisateur $idcandidat): self
    {
        $this->idcandidat = $idcandidat;

        return $this;
    }


}
