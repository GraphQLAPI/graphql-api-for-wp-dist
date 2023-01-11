<?php

declare (strict_types=1);
namespace PoPCMSSchema\Menus\TypeResolvers\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoPCMSSchema\Menus\ObjectModels\MenuItem;
use PoPCMSSchema\Menus\RelationalTypeDataLoaders\ObjectType\MenuItemTypeDataLoader;
class MenuItemObjectTypeResolver extends AbstractObjectTypeResolver
{
    /**
     * @var \PoPCMSSchema\Menus\RelationalTypeDataLoaders\ObjectType\MenuItemTypeDataLoader|null
     */
    private $menuItemTypeDataLoader;
    /**
     * @param \PoPCMSSchema\Menus\RelationalTypeDataLoaders\ObjectType\MenuItemTypeDataLoader $menuItemTypeDataLoader
     */
    public final function setMenuItemTypeDataLoader($menuItemTypeDataLoader) : void
    {
        $this->menuItemTypeDataLoader = $menuItemTypeDataLoader;
    }
    protected final function getMenuItemTypeDataLoader() : MenuItemTypeDataLoader
    {
        /** @var MenuItemTypeDataLoader */
        return $this->menuItemTypeDataLoader = $this->menuItemTypeDataLoader ?? $this->instanceManager->getInstance(MenuItemTypeDataLoader::class);
    }
    public function getTypeName() : string
    {
        return 'MenuItem';
    }
    public function getTypeDescription() : ?string
    {
        return $this->__('Items (links, pages, etc) added to a menu', 'menus');
    }
    /**
     * @return string|int|null
     * @param object $object
     */
    public function getID($object)
    {
        /** @var MenuItem */
        $menuItem = $object;
        return $menuItem->id;
    }
    public function getRelationalTypeDataLoader() : RelationalTypeDataLoaderInterface
    {
        return $this->getMenuItemTypeDataLoader();
    }
}
