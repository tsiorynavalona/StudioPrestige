<?php

namespace App\Entity;

use App\Repository\CategoriesPhotosRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: CategoriesPhotosRepository::class)]
class CategoriesPhotos
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:'Ce champ ne doit pas être vide.', groups: ['edit', 'add'])]
    private ?string $label = null;

    #[ORM\ManyToMany(targetEntity: Photos::class, mappedBy: 'categoriesPhotos')]
    private Collection $photos;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message:'Ce champ ne doit pas être vide.', groups: ['edit', 'add'])]
    private ?string $descriptions = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    
    #[Assert\NotBlank(message:'Ce champ ne doit pas être vide.', groups: ['edit', 'add'])]
    #[Assert\Type(type:'float', message:'Ce champ doit être une chiffre présentant un tarif (ex : 24, 25.5, ...)', groups: ['edit', 'add'])]
    #[ORM\Column(type: 'float')]
    private ?float $tarif = null;

    #[ORM\ManyToOne(inversedBy: 'category')]
    private ?Reservation $reservation = null;

    public function __construct()
    {
        $this->photos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return Collection<int, Photos>
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhotos(Photos $photos): static
    {
        if (!$this->photos->contains($photos)) {
            $this->photos->add($photos);
        }

        return $this;
    }

    public function removePhotos(Photos $photos): static
    {
        $this->photos->removeElement($photos);

        return $this;
    }

    public function getDescriptions(): ?string
    {
        return $this->descriptions;
    }

    public function setDescriptions(?string $descriptions): static
    {
        $this->descriptions = $descriptions;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getTarif(): ?float
    {
        return $this->tarif;
    }

    public function setTarif(?string $tarif): static
    {
        $this->tarif = $tarif;

        return $this;
    }

    public function getReservation(): ?Reservation
    {
        return $this->reservation;
    }

    public function setReservation(?Reservation $reservation): static
    {
        $this->reservation = $reservation;

        return $this;
    }

}
