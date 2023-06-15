<?php

namespace App\Controller\Payment;

use App\Entity\Client;
use App\Interfaces\PaymentInterface;
use App\Services\Payment\OrderValue;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class CardPaymentController extends AbstractController implements PaymentInterface
{
    /**
     * @var OrderValue
     */
    private $orderValue;

    /**
     * @param OrderValue $orderValue
     */
    public function __construct(
        OrderValue $orderValue
    ) {
        $this->orderValue = $orderValue;
    }

    public function pay(Client $client): JsonResponse
    {
        return $this->orderValue->paymentProcess($client);
    }
}