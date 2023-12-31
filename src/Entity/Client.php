<?php

namespace App\Entity;

use App\Enum\ClientStatus;
use App\Enum\OrderStatus;
use App\Repository\ClientRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Stores information about clients, including their name,
 * card details, order history, and available funds.
 */
#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column]
    private float $money;

    #[ORM\Column(length: 32)]
    private string $name;

    #[ORM\Column(nullable: true)]
    private ?string $cardNumber = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTime $cardExpirationDate = null;

    #[ORM\Column(nullable: true)]
    private ?int $cardCvv = null;

    #[ORM\ManyToOne(inversedBy: 'clients')]
    private ?Waiter $waiter = null;

    #[ORM\OneToOne(mappedBy: 'client', cascade: ['detach'])]
    private ?Order $connectedOrder = null;

    #[ORM\Column]
    private int $status;

    #[ORM\Column(length: 32, nullable: false)]
    private string $paymentMethod;

    public function getId(): int
    {
        return $this->id;
    }

    public function getMoney(): float
    {
        return $this->money;
    }

    public function setMoney(float $money): static
    {
        $this->money = $money;

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

    public function getCardNumber(): ?string
    {
        return $this->cardNumber;
    }

    public function setCardNumber(string $cardNumber): static
    {
        $this->cardNumber = $cardNumber;

        return $this;
    }

    public function getCardExpirationDate(): ?\DateTime
    {
        return $this->cardExpirationDate;
    }

    public function setCardExpirationDate(\DateTime $cardExpirationDate): static
    {
        $this->cardExpirationDate = $cardExpirationDate;

        return $this;
    }

    public function getCardCvv(): ?int
    {
        return $this->cardCvv;
    }

    public function setCardCvv(int $cardCvv): static
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

    public function setConnectedOrder(?Order $connectedOrder): static
    {
        $connectedOrder->setClient($this);
        $this->connectedOrder = $connectedOrder;

        return $this;
    }

    public function getStatus(): ClientStatus
    {
        return ClientStatus::tryFrom($this->status);
    }

    public function setStatus(ClientStatus $status): static
    {
        $this->status = $status->value;

        return $this;
    }

    public function isEnoughMoneyForOrder(): bool
    {
        $orderAmountSum = $this->connectedOrder->getPrice() + $this->connectedOrder->calculateTips();
        return $this->money >= $orderAmountSum;
    }

    public function isCardValid(): bool
    {
        if (!$this->cardExpirationDate) {
            return false;
        }
        return $this->cardExpirationDate > new \DateTime();
    }

    public function getPaymentMethod(): string
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod(string $paymentMethod): static
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    public function getRestaurant(): Restaurant
    {
        return $this->connectedOrder->getWaiter()->getRestaurant();
    }

    public function payOrder(): void
    {
        $order = $this->getConnectedOrder();
        $restaurant =  $this->getRestaurant();
        $restOfMoney = $this->getMoney() - ($order->getPrice() + $order->calculateTips());
        $this->setMoney($restOfMoney);
        $restaurantBalance = $restaurant->getBalance() + $order->getPrice();
        $restaurant->setBalance($restaurantBalance);
        $order->setStatus(OrderStatus::DONE);
        $this->setStatus(ClientStatus::ORDER_PAYED);
    }
}
