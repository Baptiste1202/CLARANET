<?php

namespace App\Entity;

use App\Repository\OptionnelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OptionnelRepository ::class)]
#[ORM\Table(name: '`optionnel`')]
class Optionnel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $label_option = null;

    /**
     * @var Collection<int, Vehicule>
     */
    #[ORM\ManyToMany(targetEntity: Vehicule::class, mappedBy: 'optionnel')]
    private Collection $vehicules;

    public function __construct()
    {
        $this->vehicules = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabelOption(): ?string
    {
        return $this->label_option;
    }

    public function setLabelOption(?string $label_option): static
    {
        $this->label_option = $label_option;

        return $this;
    }

    /**
     * @return Collection<int, vehicules>
     */
    public function getVehicules(): Collection
    {
        return $this->vehicules;
    }

    public function addVehicule(Vehicule $vehicule): static
    {
        if (!$this->vehicules->contains($vehicule)) {
            $this->vehicules->add($vehicule);
            $vehicule->addOptionnel($this);
        }

        return $this;
    }

    public function removeVehicule(Vehicule $vehicule): static
    {
        if ($this->vehicules->removeElement($vehicule)) {
            $vehicule->removeOptionnel($this);
        }

        return $this;
    }
}
