<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPosts\TypeResolvers\EnumType;

use PoP\ComponentModel\TypeResolvers\EnumType\AbstractEnumTypeResolver;
use PoPCMSSchema\CustomPosts\Constants\CustomPostOrderBy;
class CustomPostOrderByEnumTypeResolver extends AbstractEnumTypeResolver
{
    public function getTypeName() : string
    {
        return 'CustomPostOrderByEnum';
    }
    /**
     * @return string[]
     */
    public function getEnumValues() : array
    {
        return [CustomPostOrderBy::ID, CustomPostOrderBy::TITLE, CustomPostOrderBy::DATE];
    }
    /**
     * @param string $enumValue
     */
    public function getEnumValueDescription($enumValue) : ?string
    {
        switch ($enumValue) {
            case CustomPostOrderBy::ID:
                return $this->__('Order by ID', 'users');
            case CustomPostOrderBy::TITLE:
                return $this->__('Order by title', 'users');
            case CustomPostOrderBy::DATE:
                return $this->__('Order by date', 'users');
            default:
                return parent::getEnumValueDescription($enumValue);
        }
    }
}
