<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ShareholderRepository")
 */
class Shareholder
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Company", inversedBy="shareholders")
     */
    private $company;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $part;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Company")
     */
    private $shareholder;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getPart(): ?int
    {
        return $this->part;
    }

    public function setPart(?int $part): self
    {
        $this->part = $part;

        return $this;
    }

    public function getShareholder(): ?Company
    {
        return $this->shareholder;
    }

    public function setShareholder(?Company $shareholder): self
    {
        $this->shareholder = $shareholder;

        return $this;
    }
}
