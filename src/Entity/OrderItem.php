<?php

namespace App\Entity;

use App\Repository\OrderItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Represents an item in an order, mirroring a MenuItem from the menu.
 */
#[ORM\Entity(repositoryClass: OrderItemRepository::class)]
class OrderItem
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\OneToMany(mappedBy: 'orderItem', targetEntity: MenuItem::class)]
    private Collection $menuItem;

    #[ORM\ManyToOne(inversedBy: 'orderItems')]
    private ?Order $connectedOrder = null;

    #[ORM\Column(length: 32)]
    private string $name;

    #[ORM\Column]
    private float $price;

    #[ORM\Column]
    private string $type;

    public function __construct()
    {
        $this->menuItem = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, MenuItem>
     */
    public function getMenuItem(): Collection
    {
        return $this->menuItem;
    }

    public function addMenuItem(MenuItem $menuItem): static
    {
        if (!$this->menuItem->contains($menuItem)) {
            $this->menuItem->add($menuItem);
            $menuItem->setOrderItem($this);
        }

        return $this;
    }

    public function removeMenuItem(MenuItem $menuItem): static
    {
        if ($this->menuItem->removeElement($menuItem)) {
            // set the owning side to null (unless already changed)
            if ($menuItem->getOrderItem() === $this) {
                $menuItem->setOrderItem(null);
            }
        }

        return $this;
    }

    public function getConnectedOrder(): ?Order
    {
        return $this->connectedOrder;
    }

    public function setConnectedOrder(?Order $connectedOrder): static
    {
        $this->connectedOrder = $connectedOrder;

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

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }
}
