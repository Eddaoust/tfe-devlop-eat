<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProjectRepository")
 */
class Project
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *     min = 2,
     *     max = 50,
     *     minMessage = "Votre nom de projet doit faire au moins 2 caractères",
     *     maxMessage = "Votre nom de projet doit faire moins de 50 caractères"
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(
     *     min = 8,
     *     max = 100,
     *     minMessage = "L'adresse doit faire au moins 8 caractères",
     *     maxMessage = "L'adresse doit faire moins de 100 caractères"
     * )
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(
     *     min = 2,
     *     max = 10,
     *     minMessage = "Le code postal doit faire au moins 2 caractères",
     *     maxMessage = "Le code postal doit faire moins de 10 caractères"
     * )
     */
    private $postalCode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(
     *     min = 2,
     *     max = 20,
     *     minMessage = "La ville doit faire au moins 2 caractères",
     *     maxMessage = "La ville doit faire moins de 20 caractères"
     * )
     */
    private $city;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\Type(
     *     type="string",
     *     message="Vous devez entrer une chaine de caractère."
     * )
     */
    private $description;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\Type(
     *     type="string",
     *     message="Vous devez entrer une chaine de caractère."
     * )
     */
    private $pointOfInterest;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Type(
     *     type="integer",
     *     message="Vous devez entrer un nombre entier."
     * )
     */
    private $fieldSize;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Type(
     *     type="integer",
     *     message="Vous devez entrer un nombre entier."
     * )
     */
    private $turnover;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Type(
     *     type="integer",
     *     message="Vous devez entrer un nombre entier."
     * )
     */
    private $lots;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Date
     */
    private $created;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Company", inversedBy="projects")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid()
     */
    private $projectOwner;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Architect", inversedBy="projects")
     * @Assert\Valid()
     */
    private $architect;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\GeneralCompany", inversedBy="projects")
     * @Assert\Valid()
     */
    private $generalCompany;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\File(mimeTypes={ "image/png", "image/jpeg" })
     * @Assert\Image(
     *     maxSize = "2048k",
     *     maxSizeMessage = "L'image est trop lourde ({{ size }} {{ suffix }}). Le poid maximum est de {{ limit }} {{ suffix }}.",
     *     minRatio = 0.5,
     *     maxRatio = 2,
     *     minRatioMessage = "Le ratio de l'image est trop petit({{ ratio }}). Le ratio minimum accepté est: {{ min_ratio }}",
     *     maxRatioMessage = "Le ratio de l'image est trop grand({{ ratio }}). Le ratio maximum accepté est: {{ max_ratio }}"
     * )
     */
    private $img1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\File(mimeTypes={ "image/png", "image/jpeg" })
     * @Assert\Image(
     *     maxSize = "2048k",
     *     maxSizeMessage = "L'image est trop lourde ({{ size }} {{ suffix }}). Le poid maximum est de {{ limit }} {{ suffix }}.",
     *     minRatio = 0.5,
     *     maxRatio = 2,
     *     minRatioMessage = "Le ratio de l'image est trop petit({{ ratio }}). Le ratio minimum accepté est: {{ min_ratio }}",
     *     maxRatioMessage = "Le ratio de l'image est trop grand({{ ratio }}). Le ratio maximum accepté est: {{ max_ratio }}"
     * )
     */
    private $img2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\File(mimeTypes={ "image/png", "image/jpeg" })
     * @Assert\Image(
     *     maxSize = "2048k",
     *     maxSizeMessage = "L'image est trop lourde ({{ size }} {{ suffix }}). Le poid maximum est de {{ limit }} {{ suffix }}.",
     *     minRatio = 0.5,
     *     maxRatio = 2,
     *     minRatioMessage = "Le ratio de l'image est trop petit({{ ratio }}). Le ratio minimum accepté est: {{ min_ratio }}",
     *     maxRatioMessage = "Le ratio de l'image est trop grand({{ ratio }}). Le ratio maximum accepté est: {{ max_ratio }}"
     * )
     */
    private $img3;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Step", cascade={"persist", "remove"})
     */
    private $steps;

    public function __construct()
    {
        $this->projectImages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(?string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPointOfInterest(): ?string
    {
        return $this->pointOfInterest;
    }

    public function setPointOfInterest(?string $pointOfInterest): self
    {
        $this->pointOfInterest = $pointOfInterest;

        return $this;
    }

    public function getFieldSize(): ?int
    {
        return $this->fieldSize;
    }

    public function setFieldSize(?int $fieldSize): self
    {
        $this->fieldSize = $fieldSize;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTurnover(): ?int
    {
        return $this->turnover;
    }

    /**
     * @param mixed $turnover
     */
    public function setTurnover(?int $turnover): self
    {
        $this->turnover = $turnover;

        return $this;
    }

    public function getLots(): ?int
    {
        return $this->lots;
    }

    public function setLots(?int $lots): self
    {
        $this->lots = $lots;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getProjectOwner(): ?Company
    {
        return $this->projectOwner;
    }

    public function setProjectOwner(?Company $projectOwner): self
    {
        $this->projectOwner = $projectOwner;

        return $this;
    }

    public function getArchitect(): ?Architect
    {
        return $this->architect;
    }

    public function setArchitect(?Architect $architect): self
    {
        $this->architect = $architect;

        return $this;
    }

    public function getGeneralCompany(): ?GeneralCompany
    {
        return $this->generalCompany;
    }

    public function setGeneralCompany(?GeneralCompany $generalCompany): self
    {
        $this->generalCompany = $generalCompany;

        return $this;
    }

    public function getImg1()
    {
        return $this->img1;
    }

    public function setImg1($img1)
    {
        $this->img1 = $img1;

        return $this;
    }

    public function getImg2()
    {
        return $this->img2;
    }

    public function setImg2($img2)
    {
        $this->img2 = $img2;

        return $this;
    }

    public function getImg3()
    {
        return $this->img3;
    }

    public function setImg3($img3)
    {
        $this->img3 = $img3;

        return $this;
    }

    public function getSteps(): ?Step
    {
        return $this->steps;
    }

    public function setSteps(?Step $steps): self
    {
        $this->steps = $steps;

        return $this;
    }
}
