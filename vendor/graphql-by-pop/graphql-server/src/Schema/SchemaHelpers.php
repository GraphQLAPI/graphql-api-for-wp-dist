<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\Schema;

use PoP\ComponentModel\Schema\SchemaDefinition;
use GraphQLByPoP\GraphQLServer\Schema\SchemaDefinition as GraphQLServerSchemaDefinition;
use PoP\ComponentModel\Schema\SchemaHelpers as ComponentModelSchemaHelpers;
class SchemaHelpers
{
    /**
     * Convert the field type from its internal representation (eg: "array:id")
     * to the GraphQL standard representation (eg: "[Post]")
     *
     * If $isNonNullableOrMandatory is `true`, a "!" is added to the type name,
     * to handle both field response and field arguments:
     *
     * - field response: isNonNullable
     * - field argument: isMandatory (its provided value can still be null)
     *
     * @param string $type
     * @param boolean|null $isNonNullableOrMandatory
     * @return string
     */
    public static function getTypeToOutputInSchema(string $type, ?bool $isNonNullableOrMandatory = \false) : string
    {
        list($arrayInstances, $convertedType) = \PoP\ComponentModel\Schema\SchemaHelpers::getTypeComponents($type);
        // Convert the type name to standards by GraphQL
        $convertedType = self::convertTypeNameToGraphQLStandard($convertedType);
        return self::convertTypeToSDLSyntax($arrayInstances, $convertedType, $isNonNullableOrMandatory);
    }
    public static function convertTypeNameToGraphQLStandard(string $typeName) : string
    {
        // If the type is a scalar value, we need to convert it to the official GraphQL type
        $conversionTypes = [\PoP\ComponentModel\Schema\SchemaDefinition::TYPE_ID => \GraphQLByPoP\GraphQLServer\Schema\SchemaDefinition::TYPE_ID, \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_STRING => \GraphQLByPoP\GraphQLServer\Schema\SchemaDefinition::TYPE_STRING, \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_INT => \GraphQLByPoP\GraphQLServer\Schema\SchemaDefinition::TYPE_INT, \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_FLOAT => \GraphQLByPoP\GraphQLServer\Schema\SchemaDefinition::TYPE_FLOAT, \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_BOOL => \GraphQLByPoP\GraphQLServer\Schema\SchemaDefinition::TYPE_BOOL, \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_OBJECT => \GraphQLByPoP\GraphQLServer\Schema\SchemaDefinition::TYPE_OBJECT, \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_MIXED => \GraphQLByPoP\GraphQLServer\Schema\SchemaDefinition::TYPE_MIXED, \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_DATE => \GraphQLByPoP\GraphQLServer\Schema\SchemaDefinition::TYPE_DATE, \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_TIME => \GraphQLByPoP\GraphQLServer\Schema\SchemaDefinition::TYPE_TIME, \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_URL => \GraphQLByPoP\GraphQLServer\Schema\SchemaDefinition::TYPE_URL, \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_EMAIL => \GraphQLByPoP\GraphQLServer\Schema\SchemaDefinition::TYPE_EMAIL, \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_IP => \GraphQLByPoP\GraphQLServer\Schema\SchemaDefinition::TYPE_IP, \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_ENUM => \GraphQLByPoP\GraphQLServer\Schema\SchemaDefinition::TYPE_ENUM, \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_ARRAY => \GraphQLByPoP\GraphQLServer\Schema\SchemaDefinition::TYPE_ARRAY, \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_INPUT_OBJECT => \GraphQLByPoP\GraphQLServer\Schema\SchemaDefinition::TYPE_INPUT_OBJECT];
        if (isset($conversionTypes[$typeName])) {
            $typeName = $conversionTypes[$typeName];
        }
        return $typeName;
    }
    protected static function convertTypeToSDLSyntax(int $arrayInstances, string $convertedType, ?bool $isNonNullableOrMandatory = \false) : string
    {
        // Wrap the type with the array brackets
        for ($i = 0; $i < $arrayInstances; $i++) {
            $convertedType = \sprintf('[%s]', $convertedType);
        }
        if ($isNonNullableOrMandatory) {
            $convertedType .= '!';
        }
        return $convertedType;
    }
}
