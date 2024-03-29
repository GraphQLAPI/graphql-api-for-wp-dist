<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\Schema;

use GraphQLByPoP\GraphQLServer\Facades\Registries\SchemaDefinitionReferenceRegistryFacade;
use GraphQLByPoP\GraphQLServer\ObjectModels\Field;
class SchemaDefinitionHelpers
{
    public const PATH_SEPARATOR = '.';
    /**
     * @param string[] $schemaDefinitionPath
     */
    public static function getSchemaDefinitionReferenceObjectID($schemaDefinitionPath) : string
    {
        return \implode(self::PATH_SEPARATOR, $schemaDefinitionPath);
    }
    /**
     * @return mixed[]
     * @param array<string,mixed> $schemaDefinition
     * @param string[] $schemaDefinitionPath
     */
    public static function &advancePointerToPath(&$schemaDefinition, $schemaDefinitionPath) : array
    {
        $schemaDefinitionPointer =& $schemaDefinition;
        foreach ($schemaDefinitionPath as $pathLevel) {
            $schemaDefinitionPointer =& $schemaDefinitionPointer[$pathLevel];
        }
        return $schemaDefinitionPointer;
    }
    /**
     * @return Field[]
     * @param array<string,mixed> $fullSchemaDefinition
     * @param string[] $fieldSchemaDefinitionPath
     */
    public static function createFieldsFromPath(&$fullSchemaDefinition, $fieldSchemaDefinitionPath) : array
    {
        $fieldSchemaDefinitionPointer = self::advancePointerToPath($fullSchemaDefinition, $fieldSchemaDefinitionPath);
        $fields = [];
        /** @var string $fieldName */
        foreach (\array_keys($fieldSchemaDefinitionPointer) as $fieldName) {
            $fields[] = new Field($fullSchemaDefinition, \array_merge($fieldSchemaDefinitionPath, [$fieldName]));
        }
        return $fields;
    }
    /**
     * @return Field[]
     * @param array<string,mixed> $fullSchemaDefinition
     * @param string[] $fieldSchemaDefinitionPath
     */
    public static function getFieldsFromPath(&$fullSchemaDefinition, $fieldSchemaDefinitionPath) : array
    {
        $fieldSchemaDefinitionPointer = self::advancePointerToPath($fullSchemaDefinition, $fieldSchemaDefinitionPath);
        $schemaDefinitionReferenceRegistry = SchemaDefinitionReferenceRegistryFacade::getInstance();
        $fields = [];
        /** @var string $fieldName */
        foreach (\array_keys($fieldSchemaDefinitionPointer) as $fieldName) {
            $schemaDefinitionReferenceObjectID = \GraphQLByPoP\GraphQLServer\Schema\SchemaDefinitionHelpers::getSchemaDefinitionReferenceObjectID(\array_merge($fieldSchemaDefinitionPath, [$fieldName]));
            /** @var Field */
            $field = $schemaDefinitionReferenceRegistry->getSchemaDefinitionReferenceObject($schemaDefinitionReferenceObjectID);
            $fields[] = $field;
        }
        return $fields;
    }
}
