<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\TypeDataLoaders;

use PoP\ComponentModel\TypeDataLoaders\AbstractTypeDataLoader;
use GraphQLByPoP\GraphQLServer\Facades\Registries\SchemaDefinitionReferenceRegistryFacade;
class SchemaDefinitionReferenceTypeDataLoader extends \PoP\ComponentModel\TypeDataLoaders\AbstractTypeDataLoader
{
    /**
     * @return AbstractSchemaDefinitionReferenceObject[]
     */
    public function getObjects(array $ids) : array
    {
        $schemaDefinitionReferenceRegistry = \GraphQLByPoP\GraphQLServer\Facades\Registries\SchemaDefinitionReferenceRegistryFacade::getInstance();
        // Filter out potential `null` results
        return \array_filter(\array_map(function ($schemaDefinitionID) use($schemaDefinitionReferenceRegistry) {
            return $schemaDefinitionReferenceRegistry->getSchemaDefinitionReference($schemaDefinitionID);
        }, $ids));
    }
}
