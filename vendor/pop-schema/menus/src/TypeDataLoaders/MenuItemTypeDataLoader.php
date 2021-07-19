<?php

declare (strict_types=1);
namespace PoPSchema\Menus\TypeDataLoaders;

use PoP\ComponentModel\TypeDataLoaders\AbstractTypeDataLoader;
use PoPSchema\Menus\Facades\MenuItemTypeAPIFacade;
class MenuItemTypeDataLoader extends AbstractTypeDataLoader
{
    public function getObjects(array $ids) : array
    {
        $menuItemTypeAPI = MenuItemTypeAPIFacade::getInstance();
        return \array_map(function ($id) use($menuItemTypeAPI) {
            return $menuItemTypeAPI->getMenuItem($id);
        }, $ids);
    }
}
