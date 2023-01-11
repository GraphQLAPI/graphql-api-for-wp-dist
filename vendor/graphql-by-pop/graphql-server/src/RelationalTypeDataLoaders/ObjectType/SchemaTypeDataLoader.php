<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType;

use GraphQLByPoP\GraphQLServer\ObjectModels\Schema;
use GraphQLByPoP\GraphQLServer\Registries\SchemaDefinitionReferenceRegistryInterface;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\SchemaObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractUseObjectDictionaryTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
class SchemaTypeDataLoader extends AbstractUseObjectDictionaryTypeDataLoader
{
    /**
     * @var \GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\SchemaObjectTypeResolver|null
     */
    private $schemaObjectTypeResolver;
    /**
     * @var \GraphQLByPoP\GraphQLServer\Registries\SchemaDefinitionReferenceRegistryInterface|null
     */
    private $schemaDefinitionReferenceRegistry;
    /**
     * @param \GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\SchemaObjectTypeResolver $schemaObjectTypeResolver
     */
    public final function setSchemaObjectTypeResolver($schemaObjectTypeResolver) : void
    {
        $this->schemaObjectTypeResolver = $schemaObjectTypeResolver;
    }
    protected final function getSchemaObjectTypeResolver() : SchemaObjectTypeResolver
    {
        /** @var SchemaObjectTypeResolver */
        return $this->schemaObjectTypeResolver = $this->schemaObjectTypeResolver ?? $this->instanceManager->getInstance(SchemaObjectTypeResolver::class);
    }
    /**
     * @param \GraphQLByPoP\GraphQLServer\Registries\SchemaDefinitionReferenceRegistryInterface $schemaDefinitionReferenceRegistry
     */
    public final function setSchemaDefinitionReferenceRegistry($schemaDefinitionReferenceRegistry) : void
    {
        $this->schemaDefinitionReferenceRegistry = $schemaDefinitionReferenceRegistry;
    }
    protected final function getSchemaDefinitionReferenceRegistry() : SchemaDefinitionReferenceRegistryInterface
    {
        /** @var SchemaDefinitionReferenceRegistryInterface */
        return $this->schemaDefinitionReferenceRegistry = $this->schemaDefinitionReferenceRegistry ?? $this->instanceManager->getInstance(SchemaDefinitionReferenceRegistryInterface::class);
    }
    public function getObjectTypeResolver() : ObjectTypeResolverInterface
    {
        return $this->getSchemaObjectTypeResolver();
    }
    /**
     * @param int|string $id
     * @return mixed
     */
    protected function getObjectTypeNewInstance($id)
    {
        $fullSchemaDefinition = $this->getSchemaDefinitionReferenceRegistry()->getFullSchemaDefinitionForGraphQL();
        return new Schema($fullSchemaDefinition, (string) $id);
    }
}
