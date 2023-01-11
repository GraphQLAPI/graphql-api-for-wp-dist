<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType;

use GraphQLByPoP\GraphQLServer\ObjectModels\TypeInterface;
use GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType\WrappingTypeOrSchemaDefinitionReferenceTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
class TypeObjectTypeResolver extends \GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\AbstractIntrospectionObjectTypeResolver
{
    /**
     * @var \GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType\WrappingTypeOrSchemaDefinitionReferenceTypeDataLoader|null
     */
    private $wrappingTypeOrSchemaDefinitionReferenceTypeDataLoader;
    /**
     * @param \GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType\WrappingTypeOrSchemaDefinitionReferenceTypeDataLoader $wrappingTypeOrSchemaDefinitionReferenceTypeDataLoader
     */
    public final function setWrappingTypeOrSchemaDefinitionReferenceTypeDataLoader($wrappingTypeOrSchemaDefinitionReferenceTypeDataLoader) : void
    {
        $this->wrappingTypeOrSchemaDefinitionReferenceTypeDataLoader = $wrappingTypeOrSchemaDefinitionReferenceTypeDataLoader;
    }
    protected final function getWrappingTypeOrSchemaDefinitionReferenceTypeDataLoader() : WrappingTypeOrSchemaDefinitionReferenceTypeDataLoader
    {
        /** @var WrappingTypeOrSchemaDefinitionReferenceTypeDataLoader */
        return $this->wrappingTypeOrSchemaDefinitionReferenceTypeDataLoader = $this->wrappingTypeOrSchemaDefinitionReferenceTypeDataLoader ?? $this->instanceManager->getInstance(WrappingTypeOrSchemaDefinitionReferenceTypeDataLoader::class);
    }
    public function getTypeName() : string
    {
        return '__Type';
    }
    public function getTypeDescription() : ?string
    {
        return $this->__('Representation of each GraphQL type in the graph', 'graphql-server');
    }
    /**
     * @return string|int|null
     * @param object $object
     */
    public function getID($object)
    {
        /** @var TypeInterface */
        $type = $object;
        return $type->getID();
    }
    public function getRelationalTypeDataLoader() : RelationalTypeDataLoaderInterface
    {
        return $this->getWrappingTypeOrSchemaDefinitionReferenceTypeDataLoader();
    }
}
