<?php

namespace App\Entity;

use App\Enum\MenuItemType;
use Doctrine\ORM\Mapping as ORM;

/**
 * Represents menu item, including their name,
 * preparation time, price, associated restaurant, and related order.
 */
#[ORM\Entity]
class MenuItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 32)]
    private string $name;

    #[ORM\Column]
    private float $price;

    #[ORM\Column(length: 8)]
    private string $time;

    #[ORM\Column]
    private int $type;

    #[ORM\ManyToOne(inversedBy: 'menuItems')]
    private ?Restaurant $restaurant = null;

    public function getId(): int
    {
        return $this->id;
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

    public function getTime(): string
    {
        return $this->time;
    }

    public function setTime(string $time): static
    {
        $this->time = $time;

        return $this;
    }

    public function getType(): MenuItemType
    {
        return MenuItemType::tryFrom($this->type);
    }

    public function setType(MenuItemType $type): static
    {
        $this->type = $type->value;

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
