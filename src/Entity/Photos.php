<?php

namespace App\Entity;

use App\Repository\PhotosRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PhotosRepository::class)]
class Photos
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:'Ce champ ne doit pas être vide.', groups: ['add','edit'])]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $alt = null;

    #[ORM\Column(length: 255)]
    private ?string $url = null;

    #[ORM\ManyToMany(targetEntity: CategoriesPhotos::class, inversedBy: 'photos')]
    private Collection $categoriesPhotos;

    #[ORM\ManyToOne(inversedBy: 'photos')]
    #[Assert\NotBlank(message:'Ce champ ne doit pas être vide.', groups: ['add','edit'])]
    private ?Photograph $id_photograph = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $descriptions = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:'Ce champ ne doit pas être vide.', groups: ['add','edit'])]
    private ?string $client = null;

    public function __construct()
    {
        $this->categoriesPhotos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getAlt(): ?string
    {
        return $this->alt;
    }

    public function setAlt(?string $alt): static
    {
        $this->alt = $alt;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): static
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return Collection<int, CategoriesPhotos>
     */
    public function getCategoriesPhotos(): Collection
    {
        return $this->categoriesPhotos;
    }

    public function addCategoriesPhoto(CategoriesPhotos $categoriesPhoto): static
    {
        if (!$this->categoriesPhotos->contains($categoriesPhoto)) {
            $this->categoriesPhotos->add($categoriesPhoto);
            $categoriesPhoto->addPhotos($this);
        }

        return $this;
    }

    public function removeCategoriesPhoto(CategoriesPhotos $categoriesPhoto): static
    {
        if ($this->categoriesPhotos->removeElement($categoriesPhoto)) {
            $categoriesPhoto->removePhotos($this);
        }

        return $this;
    }

    public function getIdPhotograph(): ?Photograph
    {
        return $this->id_photograph;
    }

    public function setIdPhotograph(?Photograph $id_photograph): static
    {
        $this->id_photograph = $id_photograph;

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

    public function getClient(): ?string
    {
        return $this->client;
    }

    public function setClient(?string $client): static
    {
        $this->client = $client;

        return $this;
    }
}
