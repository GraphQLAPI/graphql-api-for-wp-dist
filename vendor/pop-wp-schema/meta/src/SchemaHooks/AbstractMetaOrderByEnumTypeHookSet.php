<?php

declare(strict_types=1);

namespace PoPWPSchema\Meta\SchemaHooks;

use PoP\Root\App;
use PoP\ComponentModel\TypeResolvers\EnumType\EnumTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\EnumType\HookNames;
use PoP\Root\Hooks\AbstractHookSet;
use PoPWPSchema\Meta\Constants\MetaOrderBy;

abstract class AbstractMetaOrderByEnumTypeHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::addFilter(
            HookNames::ENUM_VALUES,
            \Closure::fromCallable([$this, 'getEnumValues']),
            10,
            2
        );
        App::addFilter(
            HookNames::ENUM_VALUE_DESCRIPTION,
            \Closure::fromCallable([$this, 'getEnumValueDescription']),
            10,
            3
        );
    }

    /**
     * @param string[] $enumValues
     * @return string[]
     * @param \PoP\ComponentModel\TypeResolvers\EnumType\EnumTypeResolverInterface $enumTypeResolver
     */
    public function getEnumValues($enumValues, $enumTypeResolver): array
    {
        if (!$this->isEnumTypeResolver($enumTypeResolver)) {
            return $enumValues;
        }
        return array_merge(
            $enumValues,
            [
                MetaOrderBy::META_VALUE,
            ]
        );
    }

    /**
     * @param \PoP\ComponentModel\TypeResolvers\EnumType\EnumTypeResolverInterface $enumTypeResolver
     */
    abstract protected function isEnumTypeResolver($enumTypeResolver): bool;

    /**
     * @param string|null $enumValueDescription
     * @param \PoP\ComponentModel\TypeResolvers\EnumType\EnumTypeResolverInterface $enumTypeResolver
     * @param string $enumValue
     */
    public function getEnumValueDescription(
        $enumValueDescription,
        $enumTypeResolver,
        $enumValue
    ): ?string {
        if (!$this->isEnumTypeResolver($enumTypeResolver)) {
            return $enumValueDescription;
        }
        switch ($enumValue) {
            case MetaOrderBy::META_VALUE:
                return $this->__('Order by meta value. See description for ‘meta_value‘ in: https://developer.wordpress.org/reference/classes/wp_query/#order-orderby-parameters', 'comments');
            default:
                return $enumValueDescription;
        }
    }
}
