<?php

declare (strict_types=1);
namespace PoPCMSSchema\Users\TypeResolvers\EnumType;

use PoP\ComponentModel\TypeResolvers\EnumType\AbstractEnumTypeResolver;
use PoPCMSSchema\Users\Constants\UserOrderBy;
class UserOrderByEnumTypeResolver extends AbstractEnumTypeResolver
{
    public function getTypeName() : string
    {
        return 'UserOrderByEnum';
    }
    /**
     * @return string[]
     */
    public function getEnumValues() : array
    {
        return [UserOrderBy::ID, UserOrderBy::NAME, UserOrderBy::USERNAME, UserOrderBy::DISPLAY_NAME, UserOrderBy::REGISTRATION_DATE];
    }
    /**
     * @param string $enumValue
     */
    public function getEnumValueDescription($enumValue) : ?string
    {
        switch ($enumValue) {
            case UserOrderBy::ID:
                return $this->__('Order by ID', 'users');
            case UserOrderBy::NAME:
                return $this->__('Order by name', 'users');
            case UserOrderBy::USERNAME:
                return $this->__('Order by username (login name)', 'users');
            case UserOrderBy::DISPLAY_NAME:
                return $this->__('Order by the user display name', 'users');
            case UserOrderBy::REGISTRATION_DATE:
                return $this->__('Order by registration date', 'users');
            default:
                return parent::getEnumValueDescription($enumValue);
        }
    }
}
