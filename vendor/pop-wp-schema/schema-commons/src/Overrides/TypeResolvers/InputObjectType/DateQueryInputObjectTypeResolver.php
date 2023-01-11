<?php

declare(strict_types=1);

namespace PoPWPSchema\SchemaCommons\Overrides\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPCMSSchema\SchemaCommons\TypeResolvers\InputObjectType\DateQueryInputObjectTypeResolver as UpstreamDateQueryInputObjectTypeResolver;
use PoPWPSchema\SchemaCommons\TypeResolvers\EnumType\RelationEnumTypeResolver;
use stdClass;

class DateQueryInputObjectTypeResolver extends UpstreamDateQueryInputObjectTypeResolver
{
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver|null
     */
    private $booleanScalarTypeResolver;
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver|null
     */
    private $intScalarTypeResolver;
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver|null
     */
    private $stringScalarTypeResolver;
    /**
     * @var \PoPWPSchema\SchemaCommons\TypeResolvers\EnumType\RelationEnumTypeResolver|null
     */
    private $relationEnumTypeResolver;

    /**
     * @param \PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver $booleanScalarTypeResolver
     */
    final public function setBooleanScalarTypeResolver($booleanScalarTypeResolver): void
    {
        $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
    }
    final protected function getBooleanScalarTypeResolver(): BooleanScalarTypeResolver
    {
        /** @var BooleanScalarTypeResolver */
        return $this->booleanScalarTypeResolver = $this->booleanScalarTypeResolver ?? $this->instanceManager->getInstance(BooleanScalarTypeResolver::class);
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver $intScalarTypeResolver
     */
    final public function setIntScalarTypeResolver($intScalarTypeResolver): void
    {
        $this->intScalarTypeResolver = $intScalarTypeResolver;
    }
    final protected function getIntScalarTypeResolver(): IntScalarTypeResolver
    {
        /** @var IntScalarTypeResolver */
        return $this->intScalarTypeResolver = $this->intScalarTypeResolver ?? $this->instanceManager->getInstance(IntScalarTypeResolver::class);
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver $stringScalarTypeResolver
     */
    final public function setStringScalarTypeResolver($stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        /** @var StringScalarTypeResolver */
        return $this->stringScalarTypeResolver = $this->stringScalarTypeResolver ?? $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }
    /**
     * @param \PoPWPSchema\SchemaCommons\TypeResolvers\EnumType\RelationEnumTypeResolver $relationEnumTypeResolver
     */
    final public function setRelationEnumTypeResolver($relationEnumTypeResolver): void
    {
        $this->relationEnumTypeResolver = $relationEnumTypeResolver;
    }
    final protected function getRelationEnumTypeResolver(): RelationEnumTypeResolver
    {
        /** @var RelationEnumTypeResolver */
        return $this->relationEnumTypeResolver = $this->relationEnumTypeResolver ?? $this->instanceManager->getInstance(RelationEnumTypeResolver::class);
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers(): array
    {
        return array_merge(
            parent::getInputFieldNameTypeResolvers(),
            [
                'inclusive' => $this->getBooleanScalarTypeResolver(),
                'year' => $this->getIntScalarTypeResolver(),
                'month' => $this->getIntScalarTypeResolver(),
                'week' => $this->getIntScalarTypeResolver(),
                'day' => $this->getIntScalarTypeResolver(),
                'hour' => $this->getIntScalarTypeResolver(),
                'minute' => $this->getIntScalarTypeResolver(),
                'second' => $this->getIntScalarTypeResolver(),
                'compare' => $this->getStringScalarTypeResolver(),
                'column' => $this->getStringScalarTypeResolver(),
                'relation' => $this->getRelationEnumTypeResolver(),
            ]
        );
    }

    /**
     * @param string $inputFieldName
     */
    public function getInputFieldDescription($inputFieldName): ?string
    {
        switch ($inputFieldName) {
            case 'inclusive':
                return $this->__('For after/before, whether exact value should be matched or not', 'schema-commons');
            case 'year':
                return $this->__('4 digit year (e.g. 2011)', 'schema-commons');
            case 'month':
                return $this->__('Month number (from 1 to 12)', 'schema-commons');
            case 'week':
                return $this->__('Week of the year (from 0 to 53)', 'schema-commons');
            case 'day':
                return $this->__('Day of the month (from 1 to 31)', 'schema-commons');
            case 'hour':
                return $this->__('Hour (from 0 to 23)', 'schema-commons');
            case 'minute':
                return $this->__('Minute (from 0 to 59)', 'schema-commons');
            case 'second':
                return $this->__('Second (0 to 59)', 'schema-commons');
            case 'compare':
                return $this->__('Determines and validates what comparison operator to use', 'schema-commons');
            case 'column':
                return $this->__('Posts column to query against. Default: ‘post_date’)', 'schema-commons');
            case 'relation':
                return $this->__('OR or AND, how the sub-arrays should be compared. Default: AND. Only the value from the first sub-array will be used', 'schema-commons');
            default:
                return parent::getInputFieldDescription($inputFieldName);
        }
    }

    /**
     * Integrate parameters into the "date_query" WP_Query arg
     *
     * @see https://developer.wordpress.org/reference/classes/wp_query/#date-parameters
     *
     * @param array<string,mixed> $query
     * @param stdClass|stdClass[]|array<stdClass[]> $inputValue
     */
    public function integrateInputValueToFilteringQueryArgs(&$query, $inputValue): void
    {
        /**
         * Collect all the "date_query" results, and then arrange them properly
         * as an array, with the "relation" as the first element (if defined)
         */
        if (is_array($inputValue)) {
            $innerQueries = [];
            parent::integrateInputValueToFilteringQueryArgs($innerQueries, $inputValue);
            $dateQuery = [];
            // The "relation" is defined on the first element
            if (isset($innerQueries[0]['date_query']['relation'])) {
                $dateQuery['relation'] = $innerQueries[0]['date_query']['relation'];
            }
            // Re-create an array with all the subelements
            foreach ($innerQueries as $innerQuery) {
                $dateQuery[] = $innerQuery['date_query'];
            }
            if ($dateQuery !== []) {
                $query['date_query'] = $dateQuery;
            }
            return;
        }

        /**
         * Here it's a single stdClass. Create the config for a single "date_query"
         */
        $dateQuery = [];

        // These elements must be serialized, from Date to String
        if (isset($inputValue->before)) {
            $dateQuery['before'] = $this->getDateScalarTypeResolver()->serialize($inputValue->before);
        }
        if (isset($inputValue->after)) {
            $dateQuery['after'] = $this->getDateScalarTypeResolver()->serialize($inputValue->after);
        }

        // These elements can copy directly
        $properties = [
            'year',
            'month',
            'week',
            'day',
            'hour',
            'minute',
            'second',
            'inclusive',
            'compare',
            'column',
            'relation',
        ];
        foreach ($properties as $property) {
            if (!isset($inputValue->$property)) {
                continue;
            }
            $dateQuery[$property] = $inputValue->$property;
        }

        // Assign under "date_query"
        if ($dateQuery !== []) {
            $query['date_query'] = $dateQuery;
        }
    }
}
