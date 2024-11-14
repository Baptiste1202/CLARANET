<?php

namespace App\Entity;

use App\Repository\WheelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WheelRepository::class)]
class Wheel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $size = null;

    #[ORM\ManyToOne(targetEntity: Brand::class, inversedBy: 'wheel')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Brand $brand;
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBrand(): Brand
    {
        return $this->brand;
    }

    public function setBrand(Brand $brand): static
    {
        $this->brand = $brand;

        return $this;
    }

    public function getSize(): ?string
    {
        return $this->size;
    }

    public function setSize(string $size): static
    {
        $this->size = $size;

        return $this;
    }

}
