<?php

namespace App\Tests\Unit\Services\Restaurant;

use App\Entity\Kitchener;
use App\Entity\MenuItem;
use App\Entity\Waiter;
use App\Enum\MenuItemType;
use App\Services\Restaurant\RestaurantBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;

class RestaurantBuilderTest extends TestCase
{
    private EntityManagerInterface $em;
    private EntityRepository $entityRepository;
    private RestaurantBuilder $restaurantBuilder;
    private int $amountOfHiringStaff;
    private int $amountOfAddingMenuItems;

    public function setUp(): void
    {
        $this->em = $this->createMock(EntityManagerInterface::class);
        $this->entityRepository = $this->createMock(EntityRepository::class);
        $this->waiterOne = $this->createMock(Waiter::class);
        $this->waiterTwo = $this->createMock(Waiter::class);
        $this->kitchenerOne = $this->createMock(Kitchener::class);
        $this->kitchenerTwo = $this->createMock(Kitchener::class);
        $this->menuItemOne = $this->createMock(MenuItem::class);
        $this->menuItemTwo = $this->createMock(MenuItem::class);
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
            ->willReturn([$this->waiterOne, $this->waiterTwo]);

        $this->restaurantBuilder->build();
        $builder = $this->restaurantBuilder->hireWaiters($this->amountOfHiringStaff);
        $this->assertInstanceOf(RestaurantBuilder::class, $builder);
        $this->assertCount(2, $builder->getRestaurant()->getWaiters());
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
            ->willReturn([$this->waiterOne]);

        $this->expectExceptionMessage('You dont have enough staff in pull');

        $this->restaurantBuilder->build();
        $this->restaurantBuilder->hireWaiters($this->amountOfHiringStaff);
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
            ->willReturn([$this->kitchenerOne, $this->kitchenerTwo]);

        $this->restaurantBuilder->build();
        $builder = $this->restaurantBuilder->hireKitcheners($this->amountOfHiringStaff);
        $this->assertInstanceOf(RestaurantBuilder::class, $builder);
        $this->assertCount(2, $builder->getRestaurant()->getKitcheners());
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
            ->willReturn([$this->kitchenerOne]);

        $this->expectExceptionMessage('You dont have enough staff in pull');

        $this->restaurantBuilder->hireKitcheners($this->amountOfHiringStaff);
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
            ->willReturn([$this->menuItemOne, $this->menuItemTwo]);

        $this->restaurantBuilder->build();
        $builder =$this->restaurantBuilder->fillUpMenu($this->amountOfAddingMenuItems, 'dish');
        $this->assertInstanceOf(RestaurantBuilder::class, $builder);
        $this->assertCount(2, $builder->getRestaurant()->getMenuItems());
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
            ->willReturn([$this->menuItemOne]);

        $this->expectExceptionMessage('You dont have enough dish in pull');
        $this->restaurantBuilder->build();
        $this->restaurantBuilder->fillUpMenu($this->amountOfAddingMenuItems, 'dish');
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
            ->willReturn([$this->menuItemOne, $this->menuItemTwo]);

        $this->restaurantBuilder->build();
        $builder =$this->restaurantBuilder->fillUpMenu($this->amountOfAddingMenuItems, 'drink');
        $this->assertInstanceOf(RestaurantBuilder::class, $builder);
        $this->assertCount(2, $builder->getRestaurant()->getMenuItems());
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
            ->willReturn([$this->menuItemOne]);

        $this->expectExceptionMessage('You dont have enough drink in pull');

        $this->restaurantBuilder->build();
        $this->restaurantBuilder->fillUpMenu($this->amountOfAddingMenuItems, 'drink');
    }

}