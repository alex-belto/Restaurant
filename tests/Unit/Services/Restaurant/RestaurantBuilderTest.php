<?php

namespace App\Tests\Unit\Services\Restaurant;

use App\Entity\Kitchener;
use App\Entity\MenuItem;
use App\Entity\Restaurant;
use App\Entity\Waiter;
use App\Enum\MenuItemType;
use App\Services\Restaurant\RestaurantBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;

class RestaurantBuilderTest extends TestCase
{
    private Restaurant $restaurant;
    private EntityManagerInterface $em;
    private EntityRepository $entityRepository;
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
        $this->entityRepository = $this->createMock(EntityRepository::class);
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
            ->willReturn($this->entityRepository);

        $this->entityRepository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([$this->waiter, $this->waiter]);

        $this->restaurant
            ->expects($this->exactly($this->amountOfHiringStaff))
            ->method('addWaiter')
            ->with($this->equalTo($this->waiter));

        $this->restaurantBuilder->hireWaiters($this->restaurant, $this->amountOfHiringStaff);
    }

    public function testHiringWaitersWithException(): void
    {
        $this->em
            ->expects($this->once())
            ->method('getRepository')
            ->with($this->equalTo(Waiter::class))
            ->willReturn($this->entityRepository);

        $this->entityRepository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([$this->waiter]);

        $this->expectExceptionMessage('You dont have enough staff in pull');

        $this->restaurantBuilder->hireWaiters($this->restaurant, $this->amountOfHiringStaff);
    }

    public function testHiringKitchenersSuccessfully(): void
    {
        $this->em
            ->expects($this->once())
            ->method('getRepository')
            ->with($this->equalTo(Kitchener::class))
            ->willReturn($this->entityRepository);

        $this->entityRepository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([$this->kitchener, $this->kitchener]);

        $this->restaurant
            ->expects($this->exactly($this->amountOfHiringStaff))
            ->method('addKitchener')
            ->with($this->equalTo($this->kitchener));

        $this->restaurantBuilder->hireKitcheners($this->restaurant, $this->amountOfHiringStaff);
    }

    public function testHiringKitchenersWithException(): void
    {
        $this->em
            ->expects($this->once())
            ->method('getRepository')
            ->with($this->equalTo(Kitchener::class))
            ->willReturn($this->entityRepository);

        $this->entityRepository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([$this->kitchener]);

        $this->expectExceptionMessage('You dont have enough staff in pull');

        $this->restaurantBuilder->hireKitcheners($this->restaurant, $this->amountOfHiringStaff);
    }

    public function testAddDishToRestaurantSuccessfully(): void
    {
        $this->em
            ->expects($this->once())
            ->method('getRepository')
            ->with($this->equalTo(MenuItem::class))
            ->willReturn($this->entityRepository);

        $this->entityRepository
            ->expects($this->once())
            ->method('findBy')
            ->with(['type' => MenuItemType::DISH])
            ->willReturn([$this->menuItem, $this->menuItem]);

        $this->restaurant
            ->expects($this->exactly($this->amountOfAddingMenuItems))
            ->method('addMenuItem')
            ->with($this->equalTo($this->menuItem));

        $this->restaurantBuilder->fillUpMenu($this->restaurant, $this->amountOfAddingMenuItems, 'dish');
    }

    public function testAddDishToRestaurantWithException(): void
    {
        $this->em
            ->expects($this->once())
            ->method('getRepository')
            ->with($this->equalTo(MenuItem::class))
            ->willReturn($this->entityRepository);

        $this->entityRepository
            ->expects($this->once())
            ->method('findBy')
            ->with(['type' => MenuItemType::DISH])
            ->willReturn([$this->menuItem]);

        $this->expectExceptionMessage('You dont have enough dish in pull');

        $this->restaurantBuilder->fillUpMenu($this->restaurant, $this->amountOfAddingMenuItems, 'dish');
    }

    public function testAddDrinkToRestaurantSuccessfully(): void
    {
        $this->em
            ->expects($this->once())
            ->method('getRepository')
            ->with($this->equalTo(MenuItem::class))
            ->willReturn($this->entityRepository);

        $this->entityRepository
            ->expects($this->once())
            ->method('findBy')
            ->with(['type' => MenuItemType::DRINK])
            ->willReturn([$this->menuItem, $this->menuItem]);

        $this->restaurant
            ->expects($this->exactly($this->amountOfAddingMenuItems))
            ->method('addMenuItem')
            ->with($this->equalTo($this->menuItem));

        $this->restaurantBuilder->fillUpMenu($this->restaurant, $this->amountOfAddingMenuItems, 'drink');
    }

    public function testAddDrinkToRestaurantWithException(): void
    {
        $this->em
            ->expects($this->once())
            ->method('getRepository')
            ->with($this->equalTo(MenuItem::class))
            ->willReturn($this->entityRepository);

        $this->entityRepository
            ->expects($this->once())
            ->method('findBy')
            ->with(['type' => MenuItemType::DRINK])
            ->willReturn([$this->menuItem]);

        $this->expectExceptionMessage('You dont have enough drink in pull');

        $this->restaurantBuilder->fillUpMenu($this->restaurant, $this->amountOfAddingMenuItems, 'drink');
    }

}