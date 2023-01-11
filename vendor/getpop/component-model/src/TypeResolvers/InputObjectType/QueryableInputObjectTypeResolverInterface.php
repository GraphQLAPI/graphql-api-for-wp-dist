<?php

declare (strict_types=1);
namespace PoP\ComponentModel\TypeResolvers\InputObjectType;

use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use stdClass;
/**
 * Based on GraphQL InputObject Type
 *
 * @see https://spec.graphql.org/draft/#sec-Input-Objects
 */
interface QueryableInputObjectTypeResolverInterface extends \PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface
{
    /**
     * Have the input field filter the query
     * @param string $inputFieldName
     */
    public function getInputFieldFilterInput($inputFieldName) : ?FilterInputInterface;
    /**
     * @param string $inputFieldName
     */
    public function getConsolidatedInputFieldFilterInput($inputFieldName) : ?FilterInputInterface;
    /**
     * Apply the FilterInputs to produce the filtering query
     *
     * @param array<string,mixed> $query
     * @param stdClass|stdClass[]|array<stdClass[]> $inputValue
     */
    public function integrateInputValueToFilteringQueryArgs(&$query, $inputValue) : void;
}
