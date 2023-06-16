<?php

namespace App\Controller\Client;

use App\Controller\Payment\CardPaymentController;
use App\Controller\Payment\CashPaymentController;
use App\Controller\Payment\TipsCardPaymentController;
use App\Controller\Payment\TipsCashPaymentController;
use App\Entity\Client;
use App\Repository\ClientRepository;
use App\Services\Payment\OrderValue;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PayOrderController extends AbstractController
{
    /**
     * @var ClientRepository
     */
    private $clientRepository;

    /**
     * @var OrderValue
     */
    private $orderValue;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @param ClientRepository $clientRepository
     * @param OrderValue $orderValue
     * @param EntityManagerInterface $em
     */
    public function __construct(
        ClientRepository $clientRepository,
        OrderValue $orderValue,
        EntityManagerInterface $em
    ) {
        $this->clientRepository = $clientRepository;
        $this->orderValue = $orderValue;
        $this->em = $em;
    }

    /**
     * @throws \Exception
     */
    #[Route('/payment/client/{id}', name: 'payment')]
    public function payOrder(Request $request): JsonResponse
    {
        $clientId = $request->query->get('id');
        $paymentStrategy = $request->query->get('payment_strategy');

        if ($request->query->get('tips')) {
            $tips = $request->query->get('tips');
        }

        /** @var Client $client */
        $client = $this->clientRepository->findBy(['id' => $clientId]);
        $orderValue = $this->orderValue->getOrderValue($client);

        switch ($paymentStrategy) {
            case 'cash':
                $paymentStrategy = new CashPaymentController();
                $isEnoughMoney = $this->orderValue->isEnoughMoney($client);
                break;
            case 'card':
                $paymentStrategy = new CardPaymentController();
                $isEnoughMoney = $this->orderValue->isEnoughMoney($client);
                break;
            case 'cash_tips':
                $paymentStrategy = new TipsCardPaymentController($tips, new CardPaymentController());
                $isEnoughMoney = $this->orderValue->isEnoughMoney($client, $orderValue);
                break;
            case 'card_tips':
                $paymentStrategy = new TipsCashPaymentController($tips, new CashPaymentController());
                $isEnoughMoney = $this->orderValue->isEnoughMoney($client, $orderValue);
                break;
            default:
                return $this->json(['message' => 'wrong payment strategy']);
        }

        try {
            if ($isEnoughMoney) {
                $paymentStrategy->pay($client, $orderValue);
                $this->em->flush();
            } else {
                throw new \Exception('Customer dont have enough money!');
            }
        } catch(\Exception $e) {
            return $this->json(['message' => $e->getMessage()]);
        }

        return $this->json(['status' => 'success', 'message' => 'payment processed successfully!']);
    }
}