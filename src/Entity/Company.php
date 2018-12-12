<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CompanyRepository")
 */
class Company
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tvaNum;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $registrationNum;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $bank;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $bankAccount;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Country", inversedBy="companies")
     * @ORM\JoinColumn(nullable=false)
     */
    private $country;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CompanyCategory", inversedBy="companies")
     * @ORM\JoinColumn(nullable=false)
     */
    private $companyCategory;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Shareholder", mappedBy="company")
     */
    private $shareholders;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Project", mappedBy="projectOwner")
     */
    private $projects;

    public function __construct()
    {
        $this->shareholders = new ArrayCollection();
        $this->projects = new ArrayCollection();
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

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getTvaNum(): ?string
    {
        return $this->tvaNum;
    }

    public function setTvaNum(?string $tvaNum): self
    {
        $this->tvaNum = $tvaNum;

        return $this;
    }

    public function getRegistrationNum(): ?string
    {
        return $this->registrationNum;
    }

    public function setRegistrationNum(?string $registrationNum): self
    {
        $this->registrationNum = $registrationNum;

        return $this;
    }

    public function getBank(): ?string
    {
        return $this->bank;
    }

    public function setBank(?string $bank): self
    {
        $this->bank = $bank;

        return $this;
    }

    public function getBankAccount(): ?string
    {
        return $this->bankAccount;
    }

    public function setBankAccount(?string $bankAccount): self
    {
        $this->bankAccount = $bankAccount;

        return $this;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getCompanyCategory(): ?CompanyCategory
    {
        return $this->companyCategory;
    }

    public function setCompanyCategory(?CompanyCategory $companyCategory): self
    {
        $this->companyCategory = $companyCategory;

        return $this;
    }

    /**
     * @return Collection|Shareholder[]
     */
    public function getShareholders(): Collection
    {
        return $this->shareholders;
    }

    public function addShareholder(Shareholder $shareholder): self
    {
        if (!$this->shareholders->contains($shareholder)) {
            $this->shareholders[] = $shareholder;
            $shareholder->setCompany($this);
        }

        return $this;
    }

    public function removeShareholder(Shareholder $shareholder): self
    {
        if ($this->shareholders->contains($shareholder)) {
            $this->shareholders->removeElement($shareholder);
            // set the owning side to null (unless already changed)
            if ($shareholder->getCompany() === $this) {
                $shareholder->setCompany(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Project[]
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Project $project): self
    {
        if (!$this->projects->contains($project)) {
            $this->projects[] = $project;
            $project->setProjectOwner($this);
        }

        return $this;
    }

    public function removeProject(Project $project): self
    {
        if ($this->projects->contains($project)) {
            $this->projects->removeElement($project);
            // set the owning side to null (unless already changed)
            if ($project->getProjectOwner() === $this) {
                $project->setProjectOwner(null);
            }
        }

        return $this;
    }
}
