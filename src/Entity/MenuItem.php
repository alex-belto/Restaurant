<?php

namespace App\Entity;

use App\Repository\MenuItemRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MenuItemRepository::class)]
class MenuItem
{
    public const DISH = 1;
    public const DRINK = 2;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    protected ?string $name = null;

    #[ORM\Column]
    protected ?float $price = null;

    #[ORM\Column(length: 255)]
    protected ?string $time = null;

    #[ORM\Column(length: 255)]
    private ?int $type = null;

    #[ORM\ManyToOne(inversedBy: 'menuItems')]
    private ?Order $connectedOrder = null;

    #[ORM\ManyToOne(inversedBy: 'MenuItems')]
    private ?Restaurant $restaurant = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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

    public function getTime(): string
    {
        return $this->time;
    }

    public function setTime(string $time): static
    {
        $this->time = $time;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): static
    {
        $this->type = $type;

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
