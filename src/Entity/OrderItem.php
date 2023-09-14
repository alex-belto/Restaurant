<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Represents an item in an order, mirroring a MenuItem from the menu.
 */
#[ORM\Entity]
class OrderItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\ManyToOne(inversedBy: 'orderItem')]
    private MenuItem $menuItem;

    #[ORM\ManyToOne(inversedBy: 'orderItems')]
    #[ORM\JoinColumn(name: 'connected_order_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private ?Order $connectedOrder = null;

    #[ORM\Column(length: 32)]
    private string $name;

    #[ORM\Column]
    private float $price;

    #[ORM\Column]
    private string $type;

    public function getId(): int
    {
        return $this->id;
    }

    public function getMenuItem(): MenuItem
    {
        return $this->menuItem;
    }

    public function setMenuItem(MenuItem $menuItem): static
    {
        $this->menuItem = $menuItem;

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
