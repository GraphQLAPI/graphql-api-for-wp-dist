<?php

declare (strict_types=1);
namespace PoPSchema\Menus\TypeResolvers;

use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
use PoPSchema\Menus\Facades\MenuItemTypeAPIFacade;
use PoPSchema\Menus\TypeDataLoaders\MenuItemTypeDataLoader;
class MenuItemTypeResolver extends AbstractTypeResolver
{
    public function getTypeName() : string
    {
        return 'MenuItem';
    }
    public function getSchemaTypeDescription() : ?string
    {
        return $this->translationAPI->__('Items (links, pages, etc) added to a menu', 'menus');
    }
    /**
     * @return string|int|null
     * @param object $resultItem
     */
    public function getID($resultItem)
    {
        $menuItemTypeAPI = MenuItemTypeAPIFacade::getInstance();
        $menuItem = $resultItem;
        return $menuItemTypeAPI->getMenuItemID($menuItem);
    }
    public function getTypeDataLoaderClass() : string
    {
        return MenuItemTypeDataLoader::class;
    }
}
