<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType;

use GraphQLByPoP\GraphQLServer\ObjectModels\QueryRoot;
use GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType\QueryRootTypeDataLoader;
use PoP\ComponentModel\FieldResolvers\ObjectType\ObjectTypeFieldResolverInterface;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\CanonicalTypeNameTypeResolverTrait;
class QueryRootObjectTypeResolver extends \GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\AbstractUseRootAsSourceForSchemaObjectTypeResolver
{
    use CanonicalTypeNameTypeResolverTrait;
    /**
     * @var \GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType\QueryRootTypeDataLoader|null
     */
    private $queryRootTypeDataLoader;
    /**
     * @param \GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType\QueryRootTypeDataLoader $queryRootTypeDataLoader
     */
    public final function setQueryRootTypeDataLoader($queryRootTypeDataLoader) : void
    {
        $this->queryRootTypeDataLoader = $queryRootTypeDataLoader;
    }
    protected final function getQueryRootTypeDataLoader() : QueryRootTypeDataLoader
    {
        /** @var QueryRootTypeDataLoader */
        return $this->queryRootTypeDataLoader = $this->queryRootTypeDataLoader ?? $this->instanceManager->getInstance(QueryRootTypeDataLoader::class);
    }
    public function getTypeName() : string
    {
        return 'QueryRoot';
    }
    public function getTypeDescription() : ?string
    {
        return $this->__('Query type, starting from which the query is executed', 'graphql-server');
    }
    /**
     * @return string|int|null
     * @param object $object
     */
    public function getID($object)
    {
        /** @var QueryRoot */
        $queryRoot = $object;
        return $queryRoot->getID();
    }
    public function getRelationalTypeDataLoader() : RelationalTypeDataLoaderInterface
    {
        return $this->getQueryRootTypeDataLoader();
    }
    /**
     * @param \PoP\ComponentModel\FieldResolvers\ObjectType\ObjectTypeFieldResolverInterface $objectTypeFieldResolver
     * @param string $fieldName
     */
    public function isFieldNameConditionSatisfiedForSchema($objectTypeFieldResolver, $fieldName) : bool
    {
        return !\in_array($fieldName, ['queryRoot', 'mutationRoot']) && $objectTypeFieldResolver->getFieldMutationResolver($this, $fieldName) === null;
    }
}
