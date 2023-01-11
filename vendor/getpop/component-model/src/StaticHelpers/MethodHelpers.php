<?php

declare (strict_types=1);
namespace PoP\ComponentModel\StaticHelpers;

use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use SplObjectStorage;
use stdClass;
class MethodHelpers
{
    /**
     * @param array<string|int,EngineIterationFieldSet> $idFieldSet
     * @return SplObjectStorage<FieldInterface,array<string|int>>
     */
    public static function orderIDsByDirectFields($idFieldSet) : SplObjectStorage
    {
        /** @var SplObjectStorage<FieldInterface,array<string|int>> */
        $fieldIDs = new SplObjectStorage();
        foreach ($idFieldSet as $id => $fieldSet) {
            foreach ($fieldSet->fields as $field) {
                $ids = $fieldIDs[$field] ?? [];
                $ids[] = $id;
                $fieldIDs[$field] = $ids;
            }
        }
        return $fieldIDs;
    }
    /**
     * @param array<string|int,EngineIterationFieldSet> $idFieldSet
     * @return FieldInterface[]
     */
    public static function getFieldsFromIDFieldSet($idFieldSet) : array
    {
        /** @var FieldInterface[] */
        $fields = [];
        foreach ($idFieldSet as $id => $fieldSet) {
            $fields = \array_merge($fields, $fieldSet->fields);
        }
        return \array_values(\array_unique($fields));
    }
    /**
     * @param array<string|int,EngineIterationFieldSet> $idFieldSet
     * @param FieldInterface[] $fields
     * @return array<string|int,EngineIterationFieldSet>
     */
    public static function filterFieldsInIDFieldSet($idFieldSet, $fields) : array
    {
        $restrictedIDFieldSet = [];
        foreach ($idFieldSet as $id => $fieldSet) {
            $matchingFields = \array_intersect($fields, $fieldSet->fields);
            if ($matchingFields === []) {
                continue;
            }
            $restrictedIDFieldSet[$id] = new EngineIterationFieldSet($matchingFields, $fieldSet->conditionalFields);
        }
        return $restrictedIDFieldSet;
    }
    /**
     * Convert associative arrays (and their elements) to stdClass,
     * which is the data structure used for inputs in GraphQL.
     *
     * @param mixed[] $array
     * @return mixed[]|stdClass
     *
     * @see https://stackoverflow.com/a/4790485
     */
    public static function recursivelyConvertAssociativeArrayToStdClass($array)
    {
        foreach ($array as $key => $value) {
            if (!\is_array($value)) {
                continue;
            }
            $array[$key] = static::recursivelyConvertAssociativeArrayToStdClass($value);
        }
        $arrayIsList = function (array $array) : bool {
            if (\function_exists('array_is_list')) {
                return \array_is_list($array);
            }
            if ($array === []) {
                return \true;
            }
            $current_key = 0;
            foreach ($array as $key => $noop) {
                if ($key !== $current_key) {
                    return \false;
                }
                ++$current_key;
            }
            return \true;
        };
        // If it is an associative array, transform to stdClass
        if (!$arrayIsList($array)) {
            return (object) $array;
        }
        return $array;
    }
}
