<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass="App\Repository\CountryRepository")
 */
class Country
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=10)
     * @Assert\Type(
     *     type="string",
     *     message="Vous devez entrer une chaine de caractère."
     * )
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Type(
     *     type="string",
     *     message="Vous devez entrer une chaine de caractère."
     * )
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CompanyCategory", mappedBy="country")
     */
    private $companyCategories;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Company", mappedBy="country")
     */
    private $companies;

    public function __construct()
    {
        $this->companyCategories = new ArrayCollection();
        $this->companies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
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

    /**
     * @return Collection|CompanyCategory[]
     */
    public function getCompanyCategories(): Collection
    {
        return $this->companyCategories;
    }

    public function addCompanyCategory(CompanyCategory $companyCategory): self
    {
        if (!$this->companyCategories->contains($companyCategory)) {
            $this->companyCategories[] = $companyCategory;
            $companyCategory->setCountry($this);
        }

        return $this;
    }

    public function removeCompanyCategory(CompanyCategory $companyCategory): self
    {
        if ($this->companyCategories->contains($companyCategory)) {
            $this->companyCategories->removeElement($companyCategory);
            // set the owning side to null (unless already changed)
            if ($companyCategory->getCountry() === $this) {
                $companyCategory->setCountry(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Company[]
     */
    public function getCompanies(): Collection
    {
        return $this->companies;
    }

    public function addCompany(Company $company): self
    {
        if (!$this->companies->contains($company)) {
            $this->companies[] = $company;
            $company->setCountry($this);
        }

        return $this;
    }

    public function removeCompany(Company $company): self
    {
        if ($this->companies->contains($company)) {
            $this->companies->removeElement($company);
            // set the owning side to null (unless already changed)
            if ($company->getCountry() === $this) {
                $company->setCountry(null);
            }
        }

        return $this;
    }
}
