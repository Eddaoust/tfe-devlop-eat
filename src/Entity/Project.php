<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $postalCode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $pointOfInterest;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $fieldSize;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $turnover;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $lots;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Company", inversedBy="projects")
     * @ORM\JoinColumn(nullable=false)
     */
    private $projectOwner;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Architect", inversedBy="projects")
     */
    private $architect;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\GeneralCompany", inversedBy="projects")
     */
    private $generalCompany;

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
}
