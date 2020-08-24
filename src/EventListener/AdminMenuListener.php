<?php

declare(strict_types=1);

namespace App\EventListener;

use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class AdminMenuListener
{
    public function addSupplierToMenu(MenuBuilderEvent $menuBuilderEvent): void
    {
        $menu = $menuBuilderEvent->getMenu();

        $submenu = $menu->getChild('catalog');

        $submenu
            ->addChild('suppliers', ['route' => 'app_admin_supplier_index'])
            ->setLabel('Suppliers')
        ;
    }
}
