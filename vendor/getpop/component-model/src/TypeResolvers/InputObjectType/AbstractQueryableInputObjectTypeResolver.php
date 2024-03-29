<?php

declare (strict_types=1);
namespace PoP\ComponentModel\TypeResolvers\InputObjectType;

use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoP\ComponentModel\TypeResolvers\InputObjectType\QueryableInputObjectTypeResolverInterface;
use PoP\Root\App;
use stdClass;
abstract class AbstractQueryableInputObjectTypeResolver extends \PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractInputObjectTypeResolver implements QueryableInputObjectTypeResolverInterface
{
    /** @var array<string,?FilterInputInterface> */
    private $consolidatedInputFieldFilterInputCache = [];
    /**
     * @param string $inputFieldName
     */
    public function getInputFieldFilterInput($inputFieldName) : ?FilterInputInterface
    {
        return null;
    }
    /**
     * Consolidation of the schema inputs. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     * @param string $inputFieldName
     */
    public final function getConsolidatedInputFieldFilterInput($inputFieldName) : ?FilterInputInterface
    {
        if (\array_key_exists($inputFieldName, $this->consolidatedInputFieldFilterInputCache)) {
            return $this->consolidatedInputFieldFilterInputCache[$inputFieldName];
        }
        $this->consolidatedInputFieldFilterInputCache[$inputFieldName] = App::applyFilters(\PoP\ComponentModel\TypeResolvers\InputObjectType\HookNames::INPUT_FIELD_FILTER_INPUT, $this->getInputFieldFilterInput($inputFieldName), $this, $inputFieldName);
        return $this->consolidatedInputFieldFilterInputCache[$inputFieldName];
    }
    /**
     * The base behavior can only be applied when the value is an stdClass.
     * If it is an array, or array of arrays, then apply this logic recursively.
     *
     * @param array<string,mixed> $query
     * @param stdClass|stdClass[]|array<stdClass[]> $inputValue
     */
    public function integrateInputValueToFilteringQueryArgs(&$query, $inputValue) : void
    {
        // Here $inputValue is an array, or array of arrays
        if (\is_array($inputValue)) {
            foreach ($inputValue as $index => $inputValueElem) {
                $queryElem = [];
                $this->integrateInputValueToFilteringQueryArgs($queryElem, $inputValueElem);
                // If $inputValueElem is {}, then skip
                if ($queryElem === []) {
                    continue;
                }
                $query[$index] = $queryElem;
            }
            return;
        }
        // Here $inputValue is an stdClass
        foreach ((array) $inputValue as $inputFieldName => $inputFieldValue) {
            $this->integrateInputFieldValueToFilteringQueryArgs($inputFieldName, $query, $inputFieldValue);
        }
    }
    /**
     * @param array<string,mixed> $query
     * @param mixed $inputFieldValue
     * @param string $inputFieldName
     */
    protected function integrateInputFieldValueToFilteringQueryArgs($inputFieldName, &$query, $inputFieldValue) : void
    {
        /**
         * If the input field defines a FilterInput, apply it to obtain the filtering query
         */
        if ($filterInput = $this->getConsolidatedInputFieldFilterInput($inputFieldName)) {
            $filterInput->filterDataloadQueryArgs($query, $inputFieldValue);
            return;
        }
        $inputFieldNameTypeResolvers = $this->getConsolidatedInputFieldNameTypeResolvers();
        $inputFieldTypeResolver = $inputFieldNameTypeResolvers[$inputFieldName];
        $isQueryableInputObjectTypeResolver = $inputFieldTypeResolver instanceof QueryableInputObjectTypeResolverInterface;
        $queryableInputObjectTypeResolver = null;
        if ($isQueryableInputObjectTypeResolver) {
            /** @var QueryableInputObjectTypeResolverInterface */
            $queryableInputObjectTypeResolver = $inputFieldTypeResolver;
        }
        /**
         * Check if to copy the value directly to the filtering query args
         */
        if ($queryArgName = $this->getFilteringQueryArgNameToCopyInputFieldValue($inputFieldName)) {
            /**
             * If this input field is an InputObject, then copy as an array under the specified entry
             */
            if ($isQueryableInputObjectTypeResolver) {
                /** @var QueryableInputObjectTypeResolverInterface $queryableInputObjectTypeResolver */
                $query[$queryArgName] = [];
                $queryableInputObjectTypeResolver->integrateInputValueToFilteringQueryArgs($query[$queryArgName], $inputFieldValue);
                return;
            }
            /**
             * Copy the value under the specified entry
             */
            $query[$queryArgName] = $inputFieldValue;
            return;
        }
        /**
         * If the input field is an InputObject, recursively apply this function
         */
        if ($isQueryableInputObjectTypeResolver) {
            /** @var QueryableInputObjectTypeResolverInterface $queryableInputObjectTypeResolver */
            $queryableInputObjectTypeResolver->integrateInputValueToFilteringQueryArgs($query, $inputFieldValue);
        }
    }
    /**
     * @param string $inputFieldName
     */
    protected function getFilteringQueryArgNameToCopyInputFieldValue($inputFieldName) : ?string
    {
        return null;
    }
}
