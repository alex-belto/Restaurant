<?php

namespace App\Entity;

use App\Repository\WaiterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WaiterRepository::class)]
class Waiter
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'waiter', targetEntity: Client::class)]
    private Collection $clients;

    #[ORM\Column(nullable: true)]
    private ?float $tips = null;

    #[ORM\OneToMany(mappedBy: 'waiter', targetEntity: Order::class)]
    private Collection $orders;

    public function __construct()
    {
        $this->clients = new ArrayCollection();
        $this->orders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Client>
     */
    public function getClients(): Collection
    {
        return $this->clients;
    }

    public function addClient(Client $client): static
    {
        if (!$this->clients->contains($client)) {
            $this->clients->add($client);
            $client->setWaiter($this);
        }

        return $this;
    }

    public function removeClient(Client $client): static
    {
        if ($this->clients->removeElement($client)) {
            // set the owning side to null (unless already changed)
            if ($client->getWaiter() === $this) {
                $client->setWaiter(null);
            }
        }

        return $this;
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
            $order->setWaiter($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): static
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getWaiter() === $this) {
                $order->setWaiter(null);
            }
        }

        return $this;
    }
}
