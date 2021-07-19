<?php

declare (strict_types=1);
namespace PoPSchema\Menus\TypeDataLoaders;

use PoP\ComponentModel\TypeDataLoaders\AbstractTypeDataLoader;
use PoPSchema\Menus\Facades\MenuTypeAPIFacade;
class MenuTypeDataLoader extends AbstractTypeDataLoader
{
    public function getObjects(array $ids) : array
    {
        $menuTypeAPI = MenuTypeAPIFacade::getInstance();
        // If the menu doesn't exist, remove the `null` entry
        return \array_filter(\array_map(function ($id) use($menuTypeAPI) {
            return $menuTypeAPI->getMenu($id);
        }, $ids));
    }
}
