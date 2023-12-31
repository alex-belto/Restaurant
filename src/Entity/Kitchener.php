<?php

namespace App\Entity;

use App\Interfaces\StaffInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Stores information about kitchiners, including their orders,
 * restaurant affiliation, and tip records.
 */
#[ORM\Entity]
class Kitchener implements StaffInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(nullable: true)]
    private ?float $tips = null;

    #[ORM\OneToMany(mappedBy: 'kitchener', targetEntity: Order::class)]
    private Collection $orders;

    #[ORM\Column(length: 32)]
    private string $name;

    #[ORM\ManyToOne(cascade: ['persist', 'remove'], inversedBy: 'kitcheners')]
    private ?Restaurant $restaurant = null;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTips(): ?float
    {
        return $this->tips;
    }

    public function setTips(?float $tips): static
    {
        $this->tips = $tips;

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): static
    {
        if (!$this->orders->contains($order)) {
            $this->orders->add($order);
            $order->setKitchener($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): static
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getKitchener() === $this) {
                $order->setKitchener(null);
            }
        }

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getRestaurant(): ?Restaurant
    {
        return $this->restaurant;
    }

    public function setRestaurant(?Restaurant $restaurant): static
    {
        $this->restaurant = $restaurant;

        return $this;
    }
}
