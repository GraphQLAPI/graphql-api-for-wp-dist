<?php

declare (strict_types=1);
namespace PoPCMSSchema\Menus\RelationalTypeDataLoaders\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeDataLoader;
use PoPCMSSchema\Menus\RuntimeRegistries\MenuItemRuntimeRegistryInterface;
class MenuItemTypeDataLoader extends AbstractObjectTypeDataLoader
{
    /**
     * @var \PoPCMSSchema\Menus\RuntimeRegistries\MenuItemRuntimeRegistryInterface|null
     */
    private $menuItemRuntimeRegistry;
    /**
     * @param \PoPCMSSchema\Menus\RuntimeRegistries\MenuItemRuntimeRegistryInterface $menuItemRuntimeRegistry
     */
    public final function setMenuItemRuntimeRegistry($menuItemRuntimeRegistry) : void
    {
        $this->menuItemRuntimeRegistry = $menuItemRuntimeRegistry;
    }
    protected final function getMenuItemRuntimeRegistry() : MenuItemRuntimeRegistryInterface
    {
        /** @var MenuItemRuntimeRegistryInterface */
        return $this->menuItemRuntimeRegistry = $this->menuItemRuntimeRegistry ?? $this->instanceManager->getInstance(MenuItemRuntimeRegistryInterface::class);
    }
    /**
     * @param array<string|int> $ids
     * @return array<object|null>
     */
    public function getObjects($ids) : array
    {
        // Retrieve each item from the dynamic registry
        return \array_map(\Closure::fromCallable([$this->getMenuItemRuntimeRegistry(), 'getMenuItem']), $ids);
    }
}
