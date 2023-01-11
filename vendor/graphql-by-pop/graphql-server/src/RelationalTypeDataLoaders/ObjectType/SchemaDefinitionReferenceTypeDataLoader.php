<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType;

use GraphQLByPoP\GraphQLServer\Registries\SchemaDefinitionReferenceRegistryInterface;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeDataLoader;
class SchemaDefinitionReferenceTypeDataLoader extends AbstractObjectTypeDataLoader
{
    /**
     * @var \GraphQLByPoP\GraphQLServer\Registries\SchemaDefinitionReferenceRegistryInterface|null
     */
    private $schemaDefinitionReferenceRegistry;
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
    /**
     * @param array<string|int> $ids
     * @return array<object|null>
     */
    public function getObjects($ids) : array
    {
        /** @var string[] $ids */
        return \array_map(\Closure::fromCallable([$this->getSchemaDefinitionReferenceRegistry(), 'getSchemaDefinitionReferenceObject']), $ids);
    }
}
