<?php

namespace App\Entity;

use App\Repository\MenuItemRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Represents menu item, including their name,
 * preparation time, price, associated restaurant, and related order.
 */
#[ORM\Entity(repositoryClass: MenuItemRepository::class)]
class MenuItem
{
    public const DISH = 1;
    public const DRINK = 2;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255)]
    protected string $name;

    #[ORM\Column]
    protected float $price;

    #[ORM\Column(length: 255)]
    protected string $time;

    #[ORM\Column(length: 255)]
    private int $type;

    #[ORM\ManyToOne(inversedBy: 'MenuItems')]
    private ?Restaurant $restaurant = null;

    #[ORM\ManyToOne(inversedBy: 'menuItem')]
    private ?OrderItem $orderItem = null;

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

    public function getType(): int
    {
        return $this->type;
    }

    public function setType(int $type): static
    {
        $this->type = $type;

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

    public function getOrderItem(): ?OrderItem
    {
        return $this->orderItem;
    }

    public function setOrderItem(?OrderItem $orderItem): static
    {
        $this->orderItem = $orderItem;

        return $this;
    }
}
