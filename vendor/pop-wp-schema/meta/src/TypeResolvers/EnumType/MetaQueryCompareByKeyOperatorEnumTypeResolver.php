<?php

declare(strict_types=1);

namespace PoPWPSchema\Meta\TypeResolvers\EnumType;

use PoP\ComponentModel\TypeResolvers\EnumType\AbstractEnumTypeResolver;
use PoPWPSchema\Meta\Constants\MetaQueryCompareByOperators;

/**
 * Documentation:
 *
 * @see https://developer.wordpress.org/reference/classes/wp_meta_query/
 */
class MetaQueryCompareByKeyOperatorEnumTypeResolver extends AbstractEnumTypeResolver
{
    public function getTypeName(): string
    {
        return 'MetaQueryCompareByKeyOperatorEnum';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Operators to compare against the meta key', 'meta');
    }

    /**
     * @return string[]
     */
    public function getEnumValues(): array
    {
        return [
            MetaQueryCompareByOperators::EXISTS,
            MetaQueryCompareByOperators::NOT_EXISTS,
        ];
    }

    /**
     * @param string $enumValue
     */
    public function getEnumValueDescription($enumValue): ?string
    {
        switch ($enumValue) {
            case MetaQueryCompareByOperators::EXISTS:
                return '\'EXISTS\'';
            case MetaQueryCompareByOperators::NOT_EXISTS:
                return '\'NOT EXISTS\'';
            default:
                return parent::getEnumValueDescription($enumValue);
        }
    }
}
