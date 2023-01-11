<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\Registries;

use GraphQLByPoP\GraphQLServer\ObjectModels\SchemaDefinitionReferenceObjectInterface;
interface SchemaDefinitionReferenceRegistryInterface
{
    /**
     * It returns the full schema, expanded with all data required to satisfy GraphQL's introspection fields (starting from "__schema")
     *
     * @return array<string,mixed>
     */
    public function &getFullSchemaDefinitionForGraphQL() : array;
    /**
     * @param \GraphQLByPoP\GraphQLServer\ObjectModels\SchemaDefinitionReferenceObjectInterface $referenceObject
     */
    public function registerSchemaDefinitionReferenceObject($referenceObject) : string;
    /**
     * @param string $referenceObjectID
     */
    public function getSchemaDefinitionReferenceObject($referenceObjectID) : ?SchemaDefinitionReferenceObjectInterface;
}
