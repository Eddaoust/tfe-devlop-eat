<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StepRepository")
 */
class Step
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\Date
     */
    private $study;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\Date
     */
    private $mastery;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\Date
     */
    private $permitStart;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\Date
     */
    private $permitEnd;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\Date
     */
    private $worksStart;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\Date
     */
    private $worksEnd;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\Date
     */
    private $delivery;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStudy(): ?\DateTimeInterface
    {
        return $this->study;
    }

    public function setStudy(?\DateTimeInterface $study): self
    {
        $this->study = $study;

        return $this;
    }

    public function getMastery(): ?\DateTimeInterface
    {
        return $this->mastery;
    }

    public function setMastery(?\DateTimeInterface $mastery): self
    {
        $this->mastery = $mastery;

        return $this;
    }

    public function getPermitStart(): ?\DateTimeInterface
    {
        return $this->permitStart;
    }

    public function setPermitStart(?\DateTimeInterface $permitStart): self
    {
        $this->permitStart = $permitStart;

        return $this;
    }

    public function getPermitEnd(): ?\DateTimeInterface
    {
        return $this->permitEnd;
    }

    public function setPermitEnd(?\DateTimeInterface $permitEnd): self
    {
        $this->permitEnd = $permitEnd;

        return $this;
    }

    public function getWorksStart(): ?\DateTimeInterface
    {
        return $this->worksStart;
    }

    public function setWorksStart(?\DateTimeInterface $worksStart): self
    {
        $this->worksStart = $worksStart;

        return $this;
    }

    public function getWorksEnd(): ?\DateTimeInterface
    {
        return $this->worksEnd;
    }

    public function setWorksEnd(?\DateTimeInterface $worksEnd): self
    {
        $this->worksEnd = $worksEnd;

        return $this;
    }

    public function getDelivery(): ?\DateTimeInterface
    {
        return $this->delivery;
    }

    public function setDelivery(?\DateTimeInterface $delivery): self
    {
        $this->delivery = $delivery;

        return $this;
    }
}
