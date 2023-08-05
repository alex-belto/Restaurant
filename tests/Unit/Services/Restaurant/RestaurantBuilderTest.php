<?php

namespace App\Tests\Unit\Services\Restaurant;

use App\Entity\Kitchener;
use App\Entity\MenuItem;
use App\Entity\Restaurant;
use App\Entity\Waiter;
use App\Repository\KitchenerRepository;
use App\Repository\MenuItemRepository;
use App\Repository\WaiterRepository;
use App\Services\Restaurant\RestaurantBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use PHPUnit\Framework\TestCase;

class RestaurantBuilderTest extends TestCase
{
    private Restaurant $restaurant;
    private EntityManagerInterface $em;
    private WaiterRepository $waiterRepository;
    private KitchenerRepository $kitchenerRepository;
    private MenuItemRepository $menuItemRepository;
    private Waiter $waiter;
    private Kitchener $kitchener;
    private MenuItem $menuItem;
    private RestaurantBuilder $restaurantBuilder;
    private int $amountOfHiringStaff;
    private int $amountOfAddingMenuItems;

    public function setUp(): void
    {
        $this->restaurant = $this->createMock(Restaurant::class);
        $this->em = $this->createMock(EntityManagerInterface::class);
        $this->waiterRepository = $this->createMock(WaiterRepository::class);
        $this->kitchenerRepository = $this->createMock(KitchenerRepository::class);
        $this->menuItemRepository = $this->createMock(MenuItemRepository::class);
        $this->waiter = $this->createMock(Waiter::class);
        $this->kitchener = $this->createMock(Kitchener::class);
        $this->menuItem = $this->createMock(MenuItem::class);
        $this->restaurantBuilder = new RestaurantBuilder($this->em);
        $this->amountOfHiringStaff = 2;
        $this->amountOfAddingMenuItems = 2;
    }

    public function testHiringWaitersSuccessfully(): void
    {
        $this->em
            ->expects($this->once())
            ->method('getRepository')
            ->with($this->equalTo(Waiter::class))
            ->willReturn($this->waiterRepository);

        $this->waiterRepository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([$this->waiter, $this->waiter]);

        $this->restaurant
            ->expects($this->exactly($this->amountOfHiringStaff))
            ->method('addWaiter');

        $this->restaurantBuilder->hireWaiters($this->restaurant, $this->amountOfHiringStaff);
    }

    public function testHiringWaitersWithException(): void
    {
        $this->em
            ->expects($this->once())
            ->method('getRepository')
            ->with($this->equalTo(Waiter::class))
            ->willReturn($this->waiterRepository);

        $this->waiterRepository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([$this->waiter]);

        $this->expectException(Exception::class);

        $this->restaurantBuilder->hireWaiters($this->restaurant, $this->amountOfHiringStaff);
    }

    public function testHiringKitchenersSuccessfully(): void
    {
        $this->em
            ->expects($this->once())
            ->method('getRepository')
            ->with($this->equalTo(Kitchener::class))
            ->willReturn($this->kitchenerRepository);

        $this->kitchenerRepository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([$this->kitchener, $this->kitchener]);

        $this->restaurant
            ->expects($this->exactly($this->amountOfHiringStaff))
            ->method('addKitchener');

        $this->restaurantBuilder->hireKitcheners($this->restaurant, $this->amountOfHiringStaff);
    }

    public function testHiringKitchenersWithException(): void
    {
        $this->em
            ->expects($this->once())
            ->method('getRepository')
            ->with($this->equalTo(Kitchener::class))
            ->willReturn($this->kitchenerRepository);

        $this->kitchenerRepository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([$this->kitchener]);

        $this->expectException(Exception::class);

        $this->restaurantBuilder->hireKitcheners($this->restaurant, $this->amountOfHiringStaff);
    }

    public function testAddDishToRestaurantSuccessfully(): void
    {
        $this->em
            ->expects($this->once())
            ->method('getRepository')
            ->with($this->equalTo(MenuItem::class))
            ->willReturn($this->menuItemRepository);

        $this->menuItemRepository
            ->expects($this->once())
            ->method('findBy')
            ->with(['type' => MenuItem::DISH])
            ->willReturn([$this->menuItem, $this->menuItem]);

        $this->restaurant
            ->expects($this->exactly($this->amountOfAddingMenuItems))
            ->method('addMenuItem');

        $this->restaurantBuilder->fillUpMenu($this->restaurant, $this->amountOfAddingMenuItems, 'dish');
    }

    public function testAddDishToRestaurantWithException(): void
    {
        $this->em
            ->expects($this->once())
            ->method('getRepository')
            ->with($this->equalTo(MenuItem::class))
            ->willReturn($this->menuItemRepository);

        $this->menuItemRepository
            ->expects($this->once())
            ->method('findBy')
            ->with(['type' => MenuItem::DISH])
            ->willReturn([$this->menuItem]);

        $this->expectException(Exception::class);

        $this->restaurantBuilder->fillUpMenu($this->restaurant, $this->amountOfAddingMenuItems, 'dish');
    }

    public function testAddDrinkToRestaurantSuccessfully(): void
    {
        $this->em
            ->expects($this->once())
            ->method('getRepository')
            ->with($this->equalTo(MenuItem::class))
            ->willReturn($this->menuItemRepository);

        $this->menuItemRepository
            ->expects($this->once())
            ->method('findBy')
            ->with(['type' => MenuItem::DRINK])
            ->willReturn([$this->menuItem, $this->menuItem]);

        $this->restaurant
            ->expects($this->exactly($this->amountOfAddingMenuItems))
            ->method('addMenuItem');

        $this->restaurantBuilder->fillUpMenu($this->restaurant, $this->amountOfAddingMenuItems, 'drink');
    }

    public function testAddDrinkToRestaurantWithException(): void
    {
        $this->em
            ->expects($this->once())
            ->method('getRepository')
            ->with($this->equalTo(MenuItem::class))
            ->willReturn($this->menuItemRepository);

        $this->menuItemRepository
            ->expects($this->once())
            ->method('findBy')
            ->with(['type' => MenuItem::DRINK])
            ->willReturn([$this->menuItem]);

        $this->expectException(Exception::class);

        $this->restaurantBuilder->fillUpMenu($this->restaurant, $this->amountOfAddingMenuItems, 'drink');
    }

}