<?php

declare (strict_types=1);
namespace PoPCMSSchema\Comments\ConditionalOnModule\Users\SchemaHooks;

use PoP\Root\App;
use PoP\ComponentModel\TypeResolvers\EnumType\EnumTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\EnumType\HookNames;
use PoP\Root\Hooks\AbstractHookSet;
use PoPCMSSchema\Comments\ConditionalOnModule\Users\Constants\CommentOrderBy;
use PoPCMSSchema\Comments\TypeResolvers\EnumType\CommentOrderByEnumTypeResolver;
class EnumTypeHookSet extends AbstractHookSet
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
        if (!$enumTypeResolver instanceof CommentOrderByEnumTypeResolver) {
            return $enumValues;
        }
        return \array_merge($enumValues, [CommentOrderBy::AUTHOR]);
    }
    /**
     * @param string|null $enumValueDescription
     * @param \PoP\ComponentModel\TypeResolvers\EnumType\EnumTypeResolverInterface $enumTypeResolver
     * @param string $enumValue
     */
    public function getEnumValueDescription($enumValueDescription, $enumTypeResolver, $enumValue) : ?string
    {
        if (!$enumTypeResolver instanceof CommentOrderByEnumTypeResolver) {
            return $enumValueDescription;
        }
        switch ($enumValue) {
            case CommentOrderBy::AUTHOR:
                return $this->__('Order by comment author', 'comments');
            default:
                return $enumValueDescription;
        }
    }
}
