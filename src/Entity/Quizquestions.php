<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\QuizquestionsRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

#[ORM\Entity(repositoryClass: QuizquestionsRepository::class)]
class Quizquestions
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
     * @var string
     *
     * @ORM\Column(name="question", type="string", length=100, nullable=false)
     */
    #[ORM\Column(length:100, nullable:false)]
    #[Assert\NotBlank(message:"Vous devez saisir une question.")]
    #[Assert\Length(
        min: 5,
        minMessage: "Une option doit contenir au moins {{ limit }} caractères.",
    )]
    private ?string $question = null;

    /*
    /**
     * @var string
     *
     * @ORM\Column(name="option1", type="string", length=100, nullable=false)
     */
    #[ORM\Column(length:100, nullable:false)]
    #[Assert\NotBlank(message:"Une option ne peut pas etre vide.")]
    #[Assert\Length(
        min: 2,
        max: 15,
        minMessage: "Une option doit contenir au moins {{ limit }} caractères.",
        maxMessage: "Une option ne peut contenir que {{ limit }} caractères.",
    )]
    private ?string $option1 = null;

    /*
    /**
     * @var string
     *
     * @ORM\Column(name="option2", type="string", length=100, nullable=false)
     */
    #[ORM\Column(length:100, nullable:false)]
    #[Assert\NotBlank(message:"Une option ne peut pas etre vide.")]
    #[Assert\Length(
        min: 2,
        max: 10,
        minMessage: "Une option doit contenir au moins {{ limit }} caractères.",
        maxMessage: "Une option ne peut contenir que {{ limit }} caractères.",
    )]
    private ?string $option2 = null;

    /*
    /**
     * @var string
     *
     * @ORM\Column(name="option3", type="string", length=100, nullable=false)
     */
    #[ORM\Column(length:100, nullable:false)]
    #[Assert\NotBlank(message:"Une option ne peut pas etre vide.")]
    #[Assert\Length(
        min: 2,
        max: 10,
        minMessage: "Une option doit contenir au moins {{ limit }} caractères.",
        maxMessage: "Une option ne peut contenir que {{ limit }} caractères.",
    )]
    private ?string $option3 = null;

    /*
    /**
     * @var string
     *
     * @ORM\Column(name="reponse", type="string", length=100, nullable=false)
     */
    #[ORM\Column(length:100)]
    private ?string $reponse = null;

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

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('question', new Assert\Regex([
            'pattern' => '/^[a-zA-Z]/',
            'match' => true,
            'message' => 'La question doit commencer par une lettre.',
        ]));
        $metadata->addPropertyConstraint('question', new Assert\Regex([
            'pattern' => '/\?$/',
            'match' => true,
            'message' => 'La question doit se terminer par un \'?\'.',
        ]));

        $metadata->addPropertyConstraint('option1', new Assert\Regex([
            'pattern' => '/^[a-zA-Z]/',
            'match' => true,
            'message' => 'Les options doivent commencer par une lettre.',
        ]));

        $metadata->addPropertyConstraint('option2', new Assert\Regex([
            'pattern' => '/^[a-zA-Z]/',
            'match' => true,
            'message' => 'Les options doivent commencer par une lettre.',
        ]));
        $metadata->addPropertyConstraint('option2', new Assert\Regex([
            'pattern' => '/^[a-zA-Z]/',
            'match' => true,
            'message' => 'Les options doivent commencer par une lettre.',
        ]));

       
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getOption1(): ?string
    {
        return $this->option1;
    }

    public function setOption1(string $option1): self
    {
        $this->option1 = $option1;

        return $this;
    }

    public function getOption2(): ?string
    {
        return $this->option2;
    }

    public function setOption2(string $option2): self
    {
        $this->option2 = $option2;

        return $this;
    }

    public function getOption3(): ?string
    {
        return $this->option3;
    }

    public function setOption3(string $option3): self
    {
        $this->option3 = $option3;

        return $this;
    }

    public function getReponse(): ?string
    {
        return $this->reponse;
    }

    public function setReponse(string $reponse): self
    {
        $this->reponse = $reponse;

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


}
