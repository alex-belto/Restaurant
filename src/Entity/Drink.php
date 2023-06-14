<?php

namespace App\Entity;

use App\Repository\DrinkRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DrinkRepository::class)]
class Drink extends MenuItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    public function __construct(string $name, float $price, string $time) {
        $this->name = $name;
        $this->price = $price;
        $this->time = $time;
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}
