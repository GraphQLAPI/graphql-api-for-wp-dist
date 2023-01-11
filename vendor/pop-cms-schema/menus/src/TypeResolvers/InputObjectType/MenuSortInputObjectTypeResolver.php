<?php

declare (strict_types=1);
namespace PoPCMSSchema\Menus\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoPCMSSchema\Menus\Constants\MenuOrderBy;
use PoPCMSSchema\Menus\TypeResolvers\EnumType\MenuOrderByEnumTypeResolver;
use PoPCMSSchema\SchemaCommons\TypeResolvers\InputObjectType\SortInputObjectTypeResolver;
class MenuSortInputObjectTypeResolver extends SortInputObjectTypeResolver
{
    /**
     * @var \PoPCMSSchema\Menus\TypeResolvers\EnumType\MenuOrderByEnumTypeResolver|null
     */
    private $menuSortByEnumTypeResolver;
    /**
     * @param \PoPCMSSchema\Menus\TypeResolvers\EnumType\MenuOrderByEnumTypeResolver $menuSortByEnumTypeResolver
     */
    public final function setMenuOrderByEnumTypeResolver($menuSortByEnumTypeResolver) : void
    {
        $this->menuSortByEnumTypeResolver = $menuSortByEnumTypeResolver;
    }
    protected final function getMenuOrderByEnumTypeResolver() : MenuOrderByEnumTypeResolver
    {
        /** @var MenuOrderByEnumTypeResolver */
        return $this->menuSortByEnumTypeResolver = $this->menuSortByEnumTypeResolver ?? $this->instanceManager->getInstance(MenuOrderByEnumTypeResolver::class);
    }
    public function getTypeName() : string
    {
        return 'MenuSortInput';
    }
    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers() : array
    {
        return \array_merge(parent::getInputFieldNameTypeResolvers(), ['by' => $this->getMenuOrderByEnumTypeResolver()]);
    }
    /**
     * @return mixed
     * @param string $inputFieldName
     */
    public function getInputFieldDefaultValue($inputFieldName)
    {
        switch ($inputFieldName) {
            case 'by':
                return MenuOrderBy::DATE;
            default:
                return parent::getInputFieldDefaultValue($inputFieldName);
        }
    }
}
