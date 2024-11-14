<?php

namespace App\Entity;

use App\Repository\VehiculeRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


#[ORM\Entity(repositoryClass: VehiculeRepository::class)]
#[ORM\Table(name: 'vehicules')]
#[ORM\HasLifecycleCallbacks]
#[Vich\Uploadable]
class Vehicule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Brand::class, inversedBy: 'vehicules')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Brand $brand;

    #[ORM\Column(length: 30)]
    private ?string $model = null;

    #[Vich\UploadableField(mapping: 'vehicules_images', fileNameProperty: 'imageName')]
    private ?File $imageFile = null;

    #[ORM\Column(nullable: true)]
    private ?string $imageName = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'vehicules')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $createdBy = null;

    #[ORM\Column(length: 6, nullable: true)]
    private ?string $year = null;

    #[ORM\Column(nullable: true)]
    private ?int $kilometrage = null;

    #[ORM\Column(nullable: true)]
    private ?int $price = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $categorie = null;

    #[ORM\Column(nullable: true)]
    private ?int $location = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $countDoors = null;

    #[ORM\ManyToOne(targetEntity: Wheel::class, inversedBy: 'vehicules')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Wheel $wheel;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $slug = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?DateTimeImmutable $modifiedAt = null;

    /**
     * @var Collection<int, Optionnel>
     */
    #[ORM\ManyToMany(targetEntity: Optionnel::class, cascade:['persist'], inversedBy: 'vehicules')]
    private Collection $optionnels;

    public function __construct()
    {
        $this->optionnels = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): static
    {
        $this->model = $model;

        return $this;
    }

    public function getYear(): ?string
    {
        return $this->year;
    }

    public function setYear(?string $year): static
    {
        $this->year = $year;

        return $this;
    }

    public function getKilometrage(): ?int
    {
        return $this->kilometrage;
    }

    public function setKilometrage(?int $kilometrage): static
    {
        $this->kilometrage = $kilometrage;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(?string $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getLocation(): ?int
    {
        return $this->location;
    }

    public function setLocation(?int $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getCountDoors(): ?string
    {
        return $this->countDoors ;
    }

    public function setCountDoors(?string $countDoors) : static
    {
        $this->countDoors = $countDoors;

        return $this;
    }

    public function getWheel(): Wheel
    {
        return $this->wheel;
    }

    public function setWheel(Wheel $wheel): static
    {
        $this->wheel = $wheel;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function setCreatedAt(): void
    {
        $this->createdAt = new \DateTimeImmutable();

    }

    public function getModifiedAt(): ?string
    {
        return $this->modifiedAt;
    }

    public function setModifiedAt(): void
    {
        $this->modifiedAt = new \DateTimeImmutable();
    }

    public function getOptionnels(): Collection
    {
        return $this->optionnels;
    }

    public function setOptionnels(Collection $optionnels): static
    {
        $this->optionnels = $optionnels;

        return $this;
    }

    public function removeOptionnel(Optionnel $optionnel): void
    {
        $this->optionnels->removeElement($optionnel);
    }

    public function addOptionnel(Optionnel $optionnel): static
    {
        if (!$this->optionnels->contains($optionnel)) {
            $this->optionnels->add($optionnel);
            $optionnel->addVehicule($this);
        }

        return $this;
    }

    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    public function setBrand(?Brand $brand): static
    {
        $this->brand = $brand;

        return $this;
    }

    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->modifiedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function getUserCreator(): ?User
    {
        return $this->createdBy;
    }

    public function setUserCreator(User $createdBy): void
    {
        $this->createdBy = $createdBy;
    }

}
