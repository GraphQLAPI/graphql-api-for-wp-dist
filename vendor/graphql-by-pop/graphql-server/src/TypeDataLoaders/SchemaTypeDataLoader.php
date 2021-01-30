<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\TypeDataLoaders;

use GraphQLByPoP\GraphQLServer\ObjectModels\Schema;
use GraphQLByPoP\GraphQLServer\TypeResolvers\SchemaTypeResolver;
use PoP\ComponentModel\TypeDataLoaders\AbstractTypeDataLoader;
use GraphQLByPoP\GraphQLServer\Facades\Registries\SchemaDefinitionReferenceRegistryFacade;
use PoP\ComponentModel\TypeDataLoaders\UseObjectDictionaryTypeDataLoaderTrait;
class SchemaTypeDataLoader extends \PoP\ComponentModel\TypeDataLoaders\AbstractTypeDataLoader
{
    use UseObjectDictionaryTypeDataLoaderTrait;
    protected function getTypeResolverClass() : string
    {
        return \GraphQLByPoP\GraphQLServer\TypeResolvers\SchemaTypeResolver::class;
    }
    protected function getTypeNewInstance($id)
    {
        return new \GraphQLByPoP\GraphQLServer\ObjectModels\Schema($this->getSchemaDefinition($id), $id);
    }
    protected function &getSchemaDefinition(string $id) : array
    {
        $schemaDefinitionReferenceRegistry = \GraphQLByPoP\GraphQLServer\Facades\Registries\SchemaDefinitionReferenceRegistryFacade::getInstance();
        return $schemaDefinitionReferenceRegistry->getFullSchemaDefinition();
    }
}
