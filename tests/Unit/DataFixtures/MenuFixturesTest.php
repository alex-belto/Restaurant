<?php

namespace App\Tests\Unit\DataFixtures;

use App\DataFixtures\MenuFixture;
use App\Entity\MenuItem;
use App\Services\Menu\MenuItemCreator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use PHPUnit\Framework\TestCase;

class MenuFixturesTest extends TestCase
{
    public function testMenuFixturesLoad(): void
    {
        $menuItemCreator = $this->createMock(MenuItemCreator::class);
        $menuItem = $this->createMock(MenuItem::class);
        $em = $this->createMock(EntityManagerInterface::class);
        $menuFixtures = new MenuFixture($menuItemCreator, $em);
        $objectManager = $this->createMock(ObjectManager::class);

        $menuItemCreator
            ->expects($this->exactly(15))
            ->method('createDish')
            ->willReturn($menuItem);

        $menuItemCreator
            ->expects($this->exactly(5))
            ->method('createDrink')
            ->willReturn($menuItem);

        $em
            ->expects($this->exactly(20))
            ->method('persist')
            ->with($this->equalTo($menuItem));

        $em
            ->expects($this->once())
            ->method('flush');

        $menuFixtures->load($objectManager);
    }

}