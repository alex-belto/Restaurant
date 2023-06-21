<?php

namespace App\Entity;

use App\Repository\RestaurantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RestaurantRepository::class)]
class Restaurant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'restaurant', targetEntity: Waiter::class)]
    private Collection $waiters;

    #[ORM\OneToMany(mappedBy: 'restaurant', targetEntity: Kitchener::class)]
    private Collection $Kitcheners;

    #[ORM\Column(nullable: true)]
    private ?float $balance = null;

    public function __construct()
    {
        $this->waiters = new ArrayCollection();
        $this->Kitcheners = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Waiter>
     */
    public function getWaiters(): Collection
    {
        return $this->waiters;
    }

    public function addWaiter(Waiter $waiter): static
    {
        if (!$this->waiters->contains($waiter)) {
            $this->waiters->add($waiter);
            $waiter->setRestaurant($this);
        }

        return $this;
    }

    public function removeWaiter(Waiter $waiter): static
    {
        if ($this->waiters->removeElement($waiter)) {
            // set the owning side to null (unless already changed)
            if ($waiter->getRestaurant() === $this) {
                $waiter->setRestaurant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Kitchener>
     */
    public function getKitcheners(): Collection
    {
        return $this->Kitcheners;
    }

    public function addKitchener(Kitchener $kitchener): static
    {
        if (!$this->Kitcheners->contains($kitchener)) {
            $this->Kitcheners->add($kitchener);
            $kitchener->setRestaurant($this);
        }

        return $this;
    }

    public function removeKitchener(Kitchener $kitchener): static
    {
        if ($this->Kitcheners->removeElement($kitchener)) {
            // set the owning side to null (unless already changed)
            if ($kitchener->getRestaurant() === $this) {
                $kitchener->setRestaurant(null);
            }
        }

        return $this;
    }

    public function getBalance(): ?float
    {
        return $this->balance;
    }

    public function setBalance(?float $balance): static
    {
        $this->balance = $balance;

        return $this;
    }
}
