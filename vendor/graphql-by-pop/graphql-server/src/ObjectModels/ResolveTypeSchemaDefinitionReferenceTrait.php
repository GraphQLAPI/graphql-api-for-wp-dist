<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use PoP\API\Schema\SchemaDefinition;
use GraphQLByPoP\GraphQLServer\Schema\SchemaDefinition as GraphQLServerSchemaDefinition;
use GraphQLByPoP\GraphQLServer\Syntax\SyntaxHelpers;
use GraphQLByPoP\GraphQLServer\ObjectModels\AbstractType;
use GraphQLByPoP\GraphQLServer\ObjectModels\InputObjectType;
use GraphQLByPoP\GraphQLServer\Schema\SchemaDefinitionHelpers;
use GraphQLByPoP\GraphQLServer\Facades\Registries\SchemaDefinitionReferenceRegistryFacade;
trait ResolveTypeSchemaDefinitionReferenceTrait
{
    protected function getTypeFromTypeName(string $typeName) : \GraphQLByPoP\GraphQLServer\ObjectModels\AbstractType
    {
        // Check if the type is non-null
        if (\GraphQLByPoP\GraphQLServer\Syntax\SyntaxHelpers::isNonNullType($typeName)) {
            return new \GraphQLByPoP\GraphQLServer\ObjectModels\NonNullType($this->fullSchemaDefinition, $this->schemaDefinitionPath, $this->getTypeFromTypeName(\GraphQLByPoP\GraphQLServer\Syntax\SyntaxHelpers::getNonNullTypeNestedTypeName($typeName)));
        }
        // Check if it is an array
        if (\GraphQLByPoP\GraphQLServer\Syntax\SyntaxHelpers::isListType($typeName)) {
            return new \GraphQLByPoP\GraphQLServer\ObjectModels\ListType($this->fullSchemaDefinition, $this->schemaDefinitionPath, $this->getTypeFromTypeName(\GraphQLByPoP\GraphQLServer\Syntax\SyntaxHelpers::getListTypeNestedTypeName($typeName)));
        }
        // Check if it is an enum type
        if ($typeName == \GraphQLByPoP\GraphQLServer\Schema\SchemaDefinition::TYPE_ENUM) {
            return new \GraphQLByPoP\GraphQLServer\ObjectModels\EnumType($this->fullSchemaDefinition, $this->schemaDefinitionPath);
        }
        // Check if it is an inputObject type
        if ($typeName == \GraphQLByPoP\GraphQLServer\Schema\SchemaDefinition::TYPE_INPUT_OBJECT) {
            return new \GraphQLByPoP\GraphQLServer\ObjectModels\InputObjectType($this->fullSchemaDefinition, $this->schemaDefinitionPath);
        }
        // By now, it's either an InterfaceType, UnionType, ObjectType or a ScalarType. Since they have all been registered, we can get their references from the registry
        $typeSchemaDefinitionPath = [\PoP\API\Schema\SchemaDefinition::ARGNAME_TYPES, $typeName];
        $schemaDefinitionID = \GraphQLByPoP\GraphQLServer\Schema\SchemaDefinitionHelpers::getID($typeSchemaDefinitionPath);
        $schemaDefinitionReferenceRegistry = \GraphQLByPoP\GraphQLServer\Facades\Registries\SchemaDefinitionReferenceRegistryFacade::getInstance();
        return $schemaDefinitionReferenceRegistry->getSchemaDefinitionReference($schemaDefinitionID);
    }
}
