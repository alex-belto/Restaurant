<?php

namespace App\Services\Menu;

use App\Entity\MenuItem;
use Doctrine\ORM\EntityManagerInterface;

/**
 * The class is responsible for creating menu items.
 */
class MenuItemCreator
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }
    public function createMenuItem(string $name, float $price, string $time, int $type): void
    {
        $menuItem = new MenuItem();
        $menuItem->setName($name);
        $menuItem->setPrice($price);
        $menuItem->setTime($time);
        $menuItem->setType($type);

        $this->em->persist($menuItem);
        $this->em->flush();
    }

}