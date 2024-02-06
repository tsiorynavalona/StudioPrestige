<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_start = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_end = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $time_start = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $time_end = null;

    #[ORM\OneToMany(mappedBy: 'reservation', targetEntity: CategoriesPhotos::class)]
    private Collection $category;

    #[ORM\OneToMany(mappedBy: 'reservation', targetEntity: Photograph::class)]
    private Collection $photograph;

    #[ORM\OneToMany(mappedBy: 'reservation', targetEntity: User::class)]
    private Collection $client;

    public function __construct()
    {
        $this->category = new ArrayCollection();
        $this->photograph = new ArrayCollection();
        $this->client = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->date_start;
    }

    public function setDateStart(\DateTimeInterface $date_start): static
    {
        $this->date_start = $date_start;

        return $this;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->date_end;
    }

    public function setDateEnd(\DateTimeInterface $date_end): static
    {
        $this->date_end = $date_end;

        return $this;
    }

    public function getTimeStart(): ?\DateTimeInterface
    {
        return $this->time_start;
    }

    public function setTimeStart(\DateTimeInterface $time_start): static
    {
        $this->time_start = $time_start;

        return $this;
    }

    public function getTimeEnd(): ?\DateTimeInterface
    {
        return $this->time_end;
    }

    public function setTimeEnd(\DateTimeInterface $time_end): static
    {
        $this->time_end = $time_end;

        return $this;
    }

    /**
     * @return Collection<int, CategoriesPhotos>
     */
    public function getCategory(): Collection
    {
        return $this->category;
    }

    public function addCategory(CategoriesPhotos $category): static
    {
        if (!$this->category->contains($category)) {
            $this->category->add($category);
            $category->setReservation($this);
        }

        return $this;
    }

    public function removeCategory(CategoriesPhotos $category): static
    {
        if ($this->category->removeElement($category)) {
            // set the owning side to null (unless already changed)
            if ($category->getReservation() === $this) {
                $category->setReservation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Photograph>
     */
    public function getPhotograph(): Collection
    {
        return $this->photograph;
    }

    public function addPhotograph(Photograph $photograph): static
    {
        if (!$this->photograph->contains($photograph)) {
            $this->photograph->add($photograph);
            $photograph->setReservation($this);
        }

        return $this;
    }

    public function removePhotograph(Photograph $photograph): static
    {
        if ($this->photograph->removeElement($photograph)) {
            // set the owning side to null (unless already changed)
            if ($photograph->getReservation() === $this) {
                $photograph->setReservation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getClient(): Collection
    {
        return $this->client;
    }

    public function addClient(User $client): static
    {
        if (!$this->client->contains($client)) {
            $this->client->add($client);
            $client->setReservation($this);
        }

        return $this;
    }

    public function removeClient(User $client): static
    {
        if ($this->client->removeElement($client)) {
            // set the owning side to null (unless already changed)
            if ($client->getReservation() === $this) {
                $client->setReservation(null);
            }
        }

        return $this;
    }
}
