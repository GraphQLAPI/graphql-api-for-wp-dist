<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\Schema;

class GraphQLSchemaHelpers
{
    /**
     * Convert the field type from its internal representation
     * to the GraphQL standard representation (eg: "[Post]")
     *
     * If $isNonNullableOrMandatory is `true`, a "!" is added to the type name,
     * to handle both field response and field arguments:
     *
     * - field response: isNonNullable
     * - field argument: isMandatory (its provided value can still be null)
     * @param string $typeName
     * @param bool|null $isNonNullableOrMandatory
     * @param bool|null $isArray
     * @param bool|null $isNonNullArrayItems
     * @param bool|null $isArrayOfArrays
     * @param bool|null $isNonNullArrayOfArraysItems
     */
    public static function getMaybeWrappedTypeName($typeName, $isNonNullableOrMandatory = \false, $isArray = \false, $isNonNullArrayItems = \false, $isArrayOfArrays = \false, $isNonNullArrayOfArraysItems = \false) : string
    {
        // Wrap the type with the array brackets
        if ($isArray) {
            if ($isArrayOfArrays) {
                if ($isNonNullArrayOfArraysItems) {
                    $typeName = self::getNonNullTypeName($typeName);
                }
                $typeName = self::getListTypeName($typeName);
            }
            if ($isNonNullArrayItems) {
                $typeName = self::getNonNullTypeName($typeName);
            }
            $typeName = self::getListTypeName($typeName);
        }
        if ($isNonNullableOrMandatory) {
            $typeName = self::getNonNullTypeName($typeName);
        }
        return $typeName;
    }
    /**
     * @param string $typeName
     */
    public static function getNonNullTypeName($typeName) : string
    {
        return \sprintf('%s!', $typeName);
    }
    /**
     * @param string $typeName
     */
    public static function getListTypeName($typeName) : string
    {
        return \sprintf('[%s]', $typeName);
    }
}
