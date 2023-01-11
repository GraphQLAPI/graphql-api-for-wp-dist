<?php

declare (strict_types=1);
namespace PoPCMSSchema\Menus\TypeResolvers\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoPCMSSchema\Menus\RelationalTypeDataLoaders\ObjectType\MenuTypeDataLoader;
use PoPCMSSchema\Menus\TypeAPIs\MenuTypeAPIInterface;
class MenuObjectTypeResolver extends AbstractObjectTypeResolver
{
    /**
     * @var \PoPCMSSchema\Menus\RelationalTypeDataLoaders\ObjectType\MenuTypeDataLoader|null
     */
    private $menuTypeDataLoader;
    /**
     * @var \PoPCMSSchema\Menus\TypeAPIs\MenuTypeAPIInterface|null
     */
    private $menuTypeAPI;
    /**
     * @param \PoPCMSSchema\Menus\RelationalTypeDataLoaders\ObjectType\MenuTypeDataLoader $menuTypeDataLoader
     */
    public final function setMenuTypeDataLoader($menuTypeDataLoader) : void
    {
        $this->menuTypeDataLoader = $menuTypeDataLoader;
    }
    protected final function getMenuTypeDataLoader() : MenuTypeDataLoader
    {
        /** @var MenuTypeDataLoader */
        return $this->menuTypeDataLoader = $this->menuTypeDataLoader ?? $this->instanceManager->getInstance(MenuTypeDataLoader::class);
    }
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
    public function getTypeName() : string
    {
        return 'Menu';
    }
    public function getTypeDescription() : ?string
    {
        return $this->__('Representation of a navigation menu', 'menus');
    }
    /**
     * @return string|int|null
     * @param object $object
     */
    public function getID($object)
    {
        $menu = $object;
        return $this->getMenuTypeAPI()->getMenuID($menu);
    }
    public function getRelationalTypeDataLoader() : RelationalTypeDataLoaderInterface
    {
        return $this->getMenuTypeDataLoader();
    }
}
