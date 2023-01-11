<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType;

use GraphQLByPoP\GraphQLServer\ObjectModels\Directive;
use GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType\SchemaDefinitionReferenceTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
class DirectiveObjectTypeResolver extends \GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\AbstractIntrospectionObjectTypeResolver
{
    /**
     * @var \GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType\SchemaDefinitionReferenceTypeDataLoader|null
     */
    private $schemaDefinitionReferenceTypeDataLoader;
    /**
     * @param \GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType\SchemaDefinitionReferenceTypeDataLoader $schemaDefinitionReferenceTypeDataLoader
     */
    public final function setSchemaDefinitionReferenceTypeDataLoader($schemaDefinitionReferenceTypeDataLoader) : void
    {
        $this->schemaDefinitionReferenceTypeDataLoader = $schemaDefinitionReferenceTypeDataLoader;
    }
    protected final function getSchemaDefinitionReferenceTypeDataLoader() : SchemaDefinitionReferenceTypeDataLoader
    {
        /** @var SchemaDefinitionReferenceTypeDataLoader */
        return $this->schemaDefinitionReferenceTypeDataLoader = $this->schemaDefinitionReferenceTypeDataLoader ?? $this->instanceManager->getInstance(SchemaDefinitionReferenceTypeDataLoader::class);
    }
    public function getTypeName() : string
    {
        return '__Directive';
    }
    public function getTypeDescription() : ?string
    {
        return $this->__('A GraphQL directive in the data graph', 'graphql-server');
    }
    /**
     * @return string|int|null
     * @param object $object
     */
    public function getID($object)
    {
        /** @var Directive */
        $directive = $object;
        return $directive->getID();
    }
    public function getRelationalTypeDataLoader() : RelationalTypeDataLoaderInterface
    {
        return $this->getSchemaDefinitionReferenceTypeDataLoader();
    }
}
