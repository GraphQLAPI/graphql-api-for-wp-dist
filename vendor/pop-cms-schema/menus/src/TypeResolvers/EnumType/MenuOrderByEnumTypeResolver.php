<?php

declare (strict_types=1);
namespace PoPCMSSchema\Menus\TypeResolvers\EnumType;

use PoP\ComponentModel\TypeResolvers\EnumType\AbstractEnumTypeResolver;
use PoPCMSSchema\Menus\Constants\MenuOrderBy;
class MenuOrderByEnumTypeResolver extends AbstractEnumTypeResolver
{
    public function getTypeName() : string
    {
        return 'MenuOrderByEnum';
    }
    /**
     * @return string[]
     */
    public function getEnumValues() : array
    {
        return [MenuOrderBy::ID, MenuOrderBy::DATE, MenuOrderBy::NAME];
    }
    /**
     * @param string $enumValue
     */
    public function getEnumValueDescription($enumValue) : ?string
    {
        switch ($enumValue) {
            case MenuOrderBy::ID:
                return $this->__('Order by ID', 'menus');
            case MenuOrderBy::DATE:
                return $this->__('Order by date', 'menus');
            case MenuOrderBy::NAME:
                return $this->__('Order by name', 'menus');
            default:
                return parent::getEnumValueDescription($enumValue);
        }
    }
}
