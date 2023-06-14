<?php

namespace App\Controller\Client;

use App\Entity\Client;
use App\Entity\MenuItem;
use App\Repository\ClientRepository;
use App\Repository\MenuItemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/order/client/{id}', name: 'order')]
class MakeOrderController extends AbstractController
{
    /**
     * @var MenuItemRepository
     */
    private $menuItemRepository;

    /**
     * @var ClientRepository
     */
    private $clientRepository;

    public function  __construct(
        MenuItemRepository $menuItemRepository,
        ClientRepository $clientRepository
    ) {
        $this->menuItemRepository = $menuItemRepository;
        $this->clientRepository = $clientRepository;
    }

    #[Route('/add/{itemId}', name: 'add_order')]
    public function addMenuItemToOrder(Request $request): JsonResponse
    {
        $clientId = $request->query->get('id');
        /** @var Client $client */
        $client = $this->clientRepository->findBy(['id' => $clientId]);

        $menuItemId = $request->query->get('itemId');
        /** @var MenuItem $menuItem */
        $menuItem = $this->menuItemRepository->findBy(['id' => $menuItemId]);

        $client->addMenuItem($menuItem);

        return $this->json(['message' => $menuItem->getName() . ' added to client id' . $clientId]);
    }

    #[Route('/remove/{itemId}', name: 'remove_order')]
    public function removeMenuItemFromOrder(Request $request): JsonResponse
    {
        $clientId = $request->query->get('id');
        /** @var Client $client */
        $client = $this->clientRepository->findBy(['id' => $clientId]);

        $menuItemId = $request->query->get('itemId');
        /** @var MenuItem $menuItem */
        $menuItem = $this->menuItemRepository->findBy(['id' => $menuItemId]);

        $client->removeMenuItem($menuItem);

        return $this->json(['message' => $menuItem->getName() . ' removed from client id' . $clientId]);
    }
}