<?php

namespace App\Entity;

use App\Enum\OrderStatus;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Stores information about orders,
 * including the menu item, client, waiter, chef, order status, and price.
 */
#[ORM\Entity]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\OneToOne(inversedBy: 'connectedOrder', cascade: ['persist', 'remove'])]
    private Client $client;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    private ?Waiter $waiter = null;

    #[ORM\Column(nullable: true)]
    private ?int $status = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    private ?Kitchener $kitchener = null;

    #[ORM\Column(nullable: true)]
    private ?int $tips = null;

    #[ORM\OneToMany(mappedBy: 'connectedOrder', targetEntity: OrderItem::class, cascade: ['remove'])]
    private Collection $orderItems;

    public function __construct()
    {
        $this->orderItems = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
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

    public function getStatus(): ?OrderStatus
    {
        return OrderStatus::tryFrom($this->status);
    }

    public function setStatus(?OrderStatus $status): static
    {
        $this->status = $status->value;

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
        $orderPrice = 0;

        foreach ($this->orderItems as $orderItem)
        {
            $price = $orderItem->getPrice();
            $orderPrice += $price;
        }

        return $orderPrice;
    }

    public function getTips(): ?int
    {
        return $this->getPrice()/100 * $this->tips;
    }

    public function setTips(?int $tips): static
    {
        $this->tips = $tips;

        return $this;
    }

    /**
     * @return Collection<int, OrderItem>
     */
    public function getOrderItems(): Collection
    {
        return $this->orderItems;
    }

    public function addOrderItem(OrderItem $orderItem): static
    {
        if (!$this->orderItems->contains($orderItem)) {
            $this->orderItems->add($orderItem);
            $orderItem->setConnectedOrder($this);
        }

        return $this;
    }

    public function removeOrderItem(OrderItem $orderItem): static
    {
        if ($this->orderItems->removeElement($orderItem)) {
            // set the owning side to null (unless already changed)
            if ($orderItem->getConnectedOrder() === $this) {
                $orderItem->setConnectedOrder(null);
            }
        }

        return $this;
    }
}
