<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\Registries;

use GraphQLByPoP\GraphQLServer\ObjectModels\AbstractSchemaDefinitionReferenceObject;
interface SchemaDefinitionReferenceRegistryInterface
{
    /**
     * It returns the full schema, expanded with all data required to satisfy GraphQL's introspection fields (starting from "__schema")
     *
     * @return array
     */
    public function &getFullSchemaDefinition() : array;
    public function registerSchemaDefinitionReference(\GraphQLByPoP\GraphQLServer\ObjectModels\AbstractSchemaDefinitionReferenceObject $referenceObject) : string;
    public function getSchemaDefinitionReference(string $referenceObjectID) : ?\GraphQLByPoP\GraphQLServer\ObjectModels\AbstractSchemaDefinitionReferenceObject;
    public function getDynamicTypes(bool $filterRepeated = \true) : array;
}
