<?php

namespace App\Entity;

use App\Interfaces\PaymentInterface;
use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $money = null;

    #[ORM\ManyToMany(targetEntity: MenuItem::class)]
    private Collection $menuItems;

    #[ORM\Column(type: Types::OBJECT)]
    private ?object $paymentStrategy = null;

    public function __construct(PaymentInterface $paymentStrategy)
    {
        $this->menuItems = new ArrayCollection();
        $this->paymentStrategy = $paymentStrategy;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMoney(): ?float
    {
        return $this->money;
    }

    public function setMoney(float $money): static
    {
        $this->money = $money;

        return $this;
    }

    /**
     * @return Collection<int, MenuItem>
     */
    public function getMenuItems(): Collection
    {
        return $this->menuItems;
    }

    public function addMenuItem(MenuItem $menuItem): static
    {
        if (!$this->menuItems->contains($menuItem)) {
            $this->menuItems->add($menuItem);
        }

        return $this;
    }

    public function removeMenuItem(MenuItem $menuItem): static
    {
        $this->menuItems->removeElement($menuItem);

        return $this;
    }

    public function getPaymentStrategy(): ?object
    {
        return $this->paymentStrategy;
    }

    public function setPaymentStrategy(object $paymentStrategy): static
    {
        $this->paymentStrategy = $paymentStrategy;

        return $this;
    }

    public function pay(): void
    {
        $this->paymentStrategy->pay($this);
    }
}
