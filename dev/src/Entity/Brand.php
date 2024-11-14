<?php

namespace App\Entity;

use App\Repository\BrandRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BrandRepository::class)]
class Brand
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $labelBrand = null;

    /**
     * @var Collection<int, Vehicule>
     */
    #[ORM\OneToMany(targetEntity: Vehicule::class, mappedBy: 'brand', orphanRemoval: true)]
    private Collection $vehicules;

    /**
     * @var Collection<int, Wheel>
     */
    #[ORM\OneToMany(targetEntity: Wheel::class, mappedBy: 'brand', orphanRemoval: true)]
    private Collection $wheels;

    public function __construct()
    {
        $this->vehicules = new ArrayCollection();
        $this->wheels = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabelBrand(): ?string
    {
        return $this->labelBrand;
    }

    public function setLabelBrand(string $brand): static
    {
        $this->labelBrand = $brand;

        return $this;
    }

    /**
     * @return Collection<int, Vehicule>
     */
    public function getVehicules(): Collection
    {
        return $this->vehicules;
    }

    public function addVehicule(Vehicule $vehicule): static
    {
        if (!$this->vehicules->contains($vehicule)) {
            $this->vehicules->add($vehicule);
            $vehicule->setBrand($this);
        }

        return $this;
    }

    public function removeVehicule(Vehicule $vehicule): static
    {
        if ($this->vehicules->removeElement($vehicule)) {
            // set the owning side to null (unless already changed)
            if ($vehicule->getBrand() === $this) {
                $vehicule->setBrand(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Wheel>
     */
    public function getWheels(): Collection
    {
        return $this->wheels;
    }

    public function addWheel(Wheel $wheel): static
    {
        if (!$this->wheels->contains($wheel)) {
            $this->wheels->add($wheel);
            $wheel->setBrand($this);
        }

        return $this;
    }

    /*
    public function removeWheel(Wheel $wheel): static
    {
        if ($this->wheels->removeElement($wheel)) {
            // set the owning side to null (unless already changed)
            if ($wheel->getBrand() === $this) {
                $wheel->setBrand(null);
            }
        }

        return $this;
    }
        */
}
