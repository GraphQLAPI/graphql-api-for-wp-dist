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
class MetaQueryCompareByArrayValueOperatorEnumTypeResolver extends AbstractEnumTypeResolver
{
    public function getTypeName(): string
    {
        return 'MetaQueryCompareByArrayValueOperatorEnum';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Operators to compare against an array value', 'meta');
    }

    /**
     * @return string[]
     */
    public function getEnumValues(): array
    {
        return [
            MetaQueryCompareByOperators::IN,
            MetaQueryCompareByOperators::NOT_IN,
            MetaQueryCompareByOperators::BETWEEN,
            MetaQueryCompareByOperators::NOT_BETWEEN,
        ];
    }

    /**
     * @param string $enumValue
     */
    public function getEnumValueDescription($enumValue): ?string
    {
        switch ($enumValue) {
            case MetaQueryCompareByOperators::IN:
                return '\'IN\'';
            case MetaQueryCompareByOperators::NOT_IN:
                return '\'NOT IN\'';
            case MetaQueryCompareByOperators::BETWEEN:
                return '\'BETWEEN\'';
            case MetaQueryCompareByOperators::NOT_BETWEEN:
                return '\'NOT BETWEEN\'';
            default:
                return parent::getEnumValueDescription($enumValue);
        }
    }
}
