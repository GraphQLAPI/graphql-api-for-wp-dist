<?php

declare (strict_types=1);
namespace PoPCMSSchema\Menus\RelationalTypeDataLoaders\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeDataLoader;
use PoPCMSSchema\Menus\TypeAPIs\MenuTypeAPIInterface;
class MenuTypeDataLoader extends AbstractObjectTypeDataLoader
{
    /**
     * @var \PoPCMSSchema\Menus\TypeAPIs\MenuTypeAPIInterface|null
     */
    private $menuTypeAPI;
    /**
     * @param \PoPCMSSchema\Menus\TypeAPIs\MenuTypeAPIInterface $menuTypeAPI
     */
    public final function setMenuTypeAPI($menuTypeAPI) : void
    {
        $this->menuTypeAPI = $menuTypeAPI;
    }
    protected final function getMenuTypeAPI() : MenuTypeAPIInterface
    {
        /** @var MenuTypeAPIInterface */
        return $this->menuTypeAPI = $this->menuTypeAPI ?? $this->instanceManager->getInstance(MenuTypeAPIInterface::class);
    }
    /**
     * @param array<string|int> $ids
     * @return array<object|null>
     */
    public function getObjects($ids) : array
    {
        // If the menu doesn't exist, remove the `null` entry
        return \array_values(\array_filter(\array_map(\Closure::fromCallable([$this->getMenuTypeAPI(), 'getMenu']), $ids)));
    }
}
