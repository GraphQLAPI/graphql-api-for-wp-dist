<?php

declare (strict_types=1);
namespace PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\SchemaHooks;

use PoP\Root\App;
use PoP\ComponentModel\TypeResolvers\EnumType\EnumTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\EnumType\HookNames;
use PoP\Root\Hooks\AbstractHookSet;
use PoPCMSSchema\Users\TypeResolvers\EnumType\UserOrderByEnumTypeResolver;
use PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\Constants\UserOrderBy;
class UserEnumTypeHookSet extends AbstractHookSet
{
    protected function init() : void
    {
        App::addFilter(HookNames::ENUM_VALUES, \Closure::fromCallable([$this, 'getEnumValues']), 10, 2);
        App::addFilter(HookNames::ENUM_VALUE_DESCRIPTION, \Closure::fromCallable([$this, 'getEnumValueDescription']), 10, 3);
    }
    /**
     * @param string[] $enumValues
     * @return string[]
     * @param \PoP\ComponentModel\TypeResolvers\EnumType\EnumTypeResolverInterface $enumTypeResolver
     */
    public function getEnumValues($enumValues, $enumTypeResolver) : array
    {
        if (!$enumTypeResolver instanceof UserOrderByEnumTypeResolver) {
            return $enumValues;
        }
        return \array_merge($enumValues, [UserOrderBy::CUSTOMPOST_COUNT]);
    }
    /**
     * @param string|null $enumValueDescription
     * @param \PoP\ComponentModel\TypeResolvers\EnumType\EnumTypeResolverInterface $enumTypeResolver
     * @param string $enumValue
     */
    public function getEnumValueDescription($enumValueDescription, $enumTypeResolver, $enumValue) : ?string
    {
        if (!$enumTypeResolver instanceof UserOrderByEnumTypeResolver) {
            return $enumValueDescription;
        }
        switch ($enumValue) {
            case UserOrderBy::CUSTOMPOST_COUNT:
                return $this->__('Order by custom post count', 'pop-users');
            default:
                return $enumValueDescription;
        }
    }
}
