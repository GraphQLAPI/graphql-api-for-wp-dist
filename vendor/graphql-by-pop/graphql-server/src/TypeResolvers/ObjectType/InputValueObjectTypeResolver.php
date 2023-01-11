<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType;

use GraphQLByPoP\GraphQLServer\ObjectModels\InputValue;
use GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType\SchemaDefinitionReferenceTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
class InputValueObjectTypeResolver extends \GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\AbstractIntrospectionObjectTypeResolver
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
        return '__InputValue';
    }
    public function getTypeDescription() : ?string
    {
        return $this->__('Representation of an input object in GraphQL', 'graphql-server');
    }
    /**
     * @return string|int|null
     * @param object $object
     */
    public function getID($object)
    {
        /** @var InputValue */
        $inputValue = $object;
        return $inputValue->getID();
    }
    public function getRelationalTypeDataLoader() : RelationalTypeDataLoaderInterface
    {
        return $this->getSchemaDefinitionReferenceTypeDataLoader();
    }
}
