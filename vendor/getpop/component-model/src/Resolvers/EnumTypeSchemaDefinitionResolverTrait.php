<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Resolvers;

use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
trait EnumTypeSchemaDefinitionResolverTrait
{
    /**
     * Add the enum values in the schema: arrays of enum name, description, deprecated and deprecation description
     *
     * @param array $schemaDefinition
     * @param TypeResolverInterface $typeResolver
     * @param string $fieldName
     * @return void
     */
    protected function doAddSchemaDefinitionEnumValuesForField(array &$schemaDefinition, array $enumValues, array $enumValueDeprecationDescriptions, array $enumValueDescriptions, ?string $enumName) : void
    {
        $enums = [];
        foreach ($enumValues as $enumValue) {
            $enum = [\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME => $enumValue];
            if ($description = $enumValueDescriptions[$enumValue] ?? null) {
                $enum[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION] = $description;
            }
            if ($deprecationDescription = $enumValueDeprecationDescriptions[$enumValue] ?? null) {
                $enum[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DEPRECATED] = \true;
                $enum[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DEPRECATIONDESCRIPTION] = $deprecationDescription;
            }
            $enums[$enumValue] = $enum;
        }
        $schemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_ENUM_VALUES] = $enums;
        // Indicate the unique name, to unify all types to the same Enum
        if ($enumName) {
            $schemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_ENUM_NAME] = $enumName;
        }
    }
}
