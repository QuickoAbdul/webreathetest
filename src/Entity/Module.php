<?php

namespace App\Entity;

use App\Repository\ModuleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ModuleRepository::class)
 */
class Module
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity=LuminosityData::class, mappedBy="Module")
     */
    private $luminosityData;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $serialNumber;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $localisation;

    public function __construct()
    {
        $this->luminosityData = new ArrayCollection();
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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, LuminosityData>
     */
    public function getLuminosityData(): Collection
    {
        return $this->luminosityData;
    }

    public function addLuminosityData(LuminosityData $luminosityData): self
    {
        if (!$this->luminosityData->contains($luminosityData)) {
            $this->luminosityData[] = $luminosityData;
            $luminosityData->setModule($this);
        }

        return $this;
    }

    public function removeLuminosityData(LuminosityData $luminosityData): self
    {
        if ($this->luminosityData->removeElement($luminosityData)) {
            // set the owning side to null (unless already changed)
            if ($luminosityData->getModule() === $this) {
                $luminosityData->setModule(null);
            }
        }

        return $this;
    }

    public function getSerialNumber(): ?string
    {
        return $this->serialNumber;
    }

    public function setSerialNumber(string $serialNumber): self
    {
        $this->serialNumber = $serialNumber;

        return $this;
    }

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(string $localisation): self
    {
        $this->localisation = $localisation;

        return $this;
    }

}
