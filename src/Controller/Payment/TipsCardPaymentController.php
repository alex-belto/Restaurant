<?php

namespace App\Controller\Payment;

use App\Entity\Client;
use App\Interfaces\PaymentInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TipsCardPaymentController extends AbstractController implements PaymentInterface
{
    /**
     * @var int
     */
    private int $tipsPercent;

    /**
     * @var CardPaymentController
     */
    private $cardPaymentController;

    /**
     * @param int $tipsPercent
     * @param CardPaymentController $cardPaymentController
     */
    public function __construct(
        int $tipsPercent,
        CardPaymentController $cardPaymentController
    ) {
        $this->tipsPercent = $tipsPercent;
        $this->cardPaymentController = $cardPaymentController;
    }

    /**
     * @throws \Exception
     */
    public function pay(Client $client, float $orderValue): void
    {
        $tips = $orderValue / 100 * $this->tipsPercent;
        $orderValue += $tips;

        $this->cardPaymentController->pay($client, $orderValue);
    }
}