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
class MetaQueryCompareByStringValueOperatorEnumTypeResolver extends AbstractEnumTypeResolver
{
    public function getTypeName(): string
    {
        return 'MetaQueryCompareByStringValueOperatorEnum';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Operators to compare against a string value', 'meta');
    }

    /**
     * @return string[]
     */
    public function getEnumValues(): array
    {
        return [
            MetaQueryCompareByOperators::EQUALS,
            MetaQueryCompareByOperators::NOT_EQUALS,
            MetaQueryCompareByOperators::LIKE,
            MetaQueryCompareByOperators::NOT_LIKE,
            MetaQueryCompareByOperators::REGEXP,
            MetaQueryCompareByOperators::NOT_REGEXP,
            MetaQueryCompareByOperators::RLIKE,
        ];
    }

    /**
     * @param string $enumValue
     */
    public function getEnumValueDescription($enumValue): ?string
    {
        switch ($enumValue) {
            case MetaQueryCompareByOperators::EQUALS:
                return '\'=\'';
            case MetaQueryCompareByOperators::NOT_EQUALS:
                return '\'!=\'';
            case MetaQueryCompareByOperators::LIKE:
                return '\'LIKE\'';
            case MetaQueryCompareByOperators::NOT_LIKE:
                return '\'NOT LIKE\'';
            case MetaQueryCompareByOperators::REGEXP:
                return '\'REGEXP\'';
            case MetaQueryCompareByOperators::NOT_REGEXP:
                return '\'NOT REGEXP\'';
            case MetaQueryCompareByOperators::RLIKE:
                return '\'RLIKE\'';
            default:
                return parent::getEnumValueDescription($enumValue);
        }
    }
}
