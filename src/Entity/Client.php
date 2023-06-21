<?php

namespace App\Entity;

use App\Repository\ClientRepository;
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

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    private ?int $cardNumber = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $cardExpirationDate = null;

    #[ORM\Column(nullable: true)]
    private ?int $cardCvv = null;

    #[ORM\ManyToOne(inversedBy: 'clients')]
    private ?Waiter $waiter = null;

    #[ORM\OneToOne(mappedBy: 'client', cascade: ['persist', 'remove'])]
    private ?Order $connectedOrder = null;

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCardNumber(): ?int
    {
        return $this->cardNumber;
    }

    public function setCardNumber(?int $cardNumber): static
    {
        $this->cardNumber = $cardNumber;

        return $this;
    }

    public function getCardExpirationDate(): ?\DateTimeInterface
    {
        return $this->cardExpirationDate;
    }

    public function setCardExpirationDate(?\DateTimeInterface $cardExpirationDate): static
    {
        $this->cardExpirationDate = $cardExpirationDate;

        return $this;
    }

    public function getCardCvv(): ?int
    {
        return $this->cardCvv;
    }

    public function setCardCvv(?int $cardCvv): static
    {
        $this->cardCvv = $cardCvv;

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

    public function getConnectedOrder(): ?Order
    {
        return $this->connectedOrder;
    }

    public function setConnectedOrder(?Order $conectedOrder): static
    {
        // unset the owning side of the relation if necessary
        if ($conectedOrder === null && $this->connectedOrder !== null) {
            $this->connectedOrder->setClient(null);
        }

        // set the owning side of the relation if necessary
        if ($conectedOrder !== null && $conectedOrder->getClient() !== $this) {
            $conectedOrder->setClient($this);
        }

        $this->conectedOrder = $conectedOrder;

        return $this;
    }
}
