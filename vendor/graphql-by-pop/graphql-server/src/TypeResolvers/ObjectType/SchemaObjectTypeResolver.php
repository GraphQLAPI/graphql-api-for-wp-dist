<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType;

use GraphQLByPoP\GraphQLServer\ObjectModels\Schema;
use GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType\SchemaTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
class SchemaObjectTypeResolver extends \GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\AbstractIntrospectionObjectTypeResolver
{
    /**
     * @var \GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType\SchemaTypeDataLoader|null
     */
    private $schemaTypeDataLoader;
    /**
     * @param \GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType\SchemaTypeDataLoader $schemaTypeDataLoader
     */
    public final function setSchemaTypeDataLoader($schemaTypeDataLoader) : void
    {
        $this->schemaTypeDataLoader = $schemaTypeDataLoader;
    }
    protected final function getSchemaTypeDataLoader() : SchemaTypeDataLoader
    {
        /** @var SchemaTypeDataLoader */
        return $this->schemaTypeDataLoader = $this->schemaTypeDataLoader ?? $this->instanceManager->getInstance(SchemaTypeDataLoader::class);
    }
    public function getTypeName() : string
    {
        return '__Schema';
    }
    public function getTypeDescription() : ?string
    {
        return $this->__('Schema type, to implement the introspection fields', 'graphql-server');
    }
    /**
     * @return string|int|null
     * @param object $object
     */
    public function getID($object)
    {
        /** @var Schema */
        $schema = $object;
        return $schema->getID();
    }
    public function getRelationalTypeDataLoader() : RelationalTypeDataLoaderInterface
    {
        return $this->getSchemaTypeDataLoader();
    }
}
