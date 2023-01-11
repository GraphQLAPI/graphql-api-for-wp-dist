<?php

declare(strict_types=1);

namespace PoPWPSchema\SchemaCommons\TypeResolvers\EnumType;

use PoP\ComponentModel\TypeResolvers\EnumType\AbstractEnumTypeResolver;
use PoPWPSchema\SchemaCommons\Constants\Relation;

/**
 * Query "relation" arg, as explained here:
 *
 * @see https://developer.wordpress.org/reference/classes/wp_query/#custom-field-post-meta-parameters
 */
class RelationEnumTypeResolver extends AbstractEnumTypeResolver
{
    public function getTypeName(): string
    {
        return 'RelationEnum';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('The logical relationship between array values in query args (for meta query, date parameters, and others) when there is more than one', 'schema-commons');
    }

    /**
     * @return string[]
     */
    public function getEnumValues(): array
    {
        return [
            Relation::AND,
            Relation::OR,
        ];
    }

    /**
     * @param string $enumValue
     */
    public function getEnumValueDescription($enumValue): ?string
    {
        switch ($enumValue) {
            case Relation::AND:
                return $this->__('`AND` relation', 'schema-commons');
            case Relation::OR:
                return $this->__('`OR` relation', 'schema-commons');
            default:
                return parent::getEnumValueDescription($enumValue);
        }
    }
}
