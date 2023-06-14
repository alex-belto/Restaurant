<?php

namespace App\Controller\Client;

use App\Entity\MenuItem;
use App\Repository\MenuItemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/menu', name: 'menu')]
class GetMenuController extends AbstractController
{
    /**
     * @var MenuItemRepository
     */
    private $menuItemRepository;

    public function __construct(MenuItemRepository $menuItemRepository)
    {
        $this->menuItemRepository = $menuItemRepository;
    }

    #[Route('/', name: 'show', methods: ['GET'])]
    public function getMenu(): JsonResponse
    {
        $menu = $this->menuItemRepository->findAll();
        return $this->json($menu);
    }

    #[Route('/{id}/time', name: 'show', methods: ['GET'])]
    public function getTimeToReady(Request $request): JsonResponse
    {
        $id = $request->query->get('id');
        /** @var MenuItem $menuItem */
        $menuItem = $this->menuItemRepository->findBy(['id'=> $id]);
        $timeToReady = $menuItem->getTime();
        return $this->json($timeToReady);
    }

}