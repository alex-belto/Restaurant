<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Stores information about orders,
 * including the menu item, client, waiter, chef, order status, and price.
 */
#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    public const READY_TO_EAT = 1;
    public const READY_TO_WAITER = 2;
    public const READY_TO_KITCHEN = 3;
    public const DONE = 4;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\OneToMany(mappedBy: 'connectedOrder', targetEntity: MenuItem::class)]
    private Collection $menuItems;

    #[ORM\OneToOne(inversedBy: 'connectedOrder', cascade: ['persist', 'remove'])]
    private Client $client;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    private ?Waiter $waiter = null;

    #[ORM\Column]
    private int $status;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    private ?Kitchener $kitchener = null;

    #[ORM\Column]
    private ?float $price = 0;

    #[ORM\Column(nullable: true)]
    private ?int $tips = null;

    public function __construct()
    {
        $this->menuItems = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, MenuItem>| MenuItem[]
     */
    public function getMenuItems(): Collection
    {
        return $this->menuItems;
    }

    public function addMenuItem(MenuItem $menuItem): static
    {
        if (!$this->menuItems->contains($menuItem)) {
            $this->menuItems->add($menuItem);
            $menuItem->setConnectedOrder($this);
            $this->price += $menuItem->getPrice();
        }

        return $this;
    }

    public function removeMenuItem(MenuItem $menuItem): static
    {
        if ($this->menuItems->removeElement($menuItem)) {
            // set the owning side to null (unless already changed)
            if ($menuItem->getConnectedOrder() === $this) {
                $menuItem->setConnectedOrder(null);
                $this->price -= $menuItem->getPrice();
            }
        }

        return $this;
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function setClient(Client $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function getWaiter(): ?Waiter
    {
        return $this->waiter;
    }

    public function setWaiter(?Waiter $waiter): static
    {
        $this->waiter = $waiter;

        return $this;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getKitchener(): ?Kitchener
    {
        return $this->kitchener;
    }

    public function setKitchener(?Kitchener $kitchener): static
    {
        $this->kitchener = $kitchener;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getTips(): ?int
    {
        return $this->tips;
    }

    public function setTips(?int $tips): static
    {
        $this->tips = $tips;

        return $this;
    }
}
