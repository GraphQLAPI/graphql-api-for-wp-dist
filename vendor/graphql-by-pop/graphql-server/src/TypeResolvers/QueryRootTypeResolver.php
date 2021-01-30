<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\TypeResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use GraphQLByPoP\GraphQLServer\ObjectModels\QueryRoot;
use PoP\Engine\TypeResolvers\ReservedNameTypeResolverTrait;
use PoP\ComponentModel\FieldResolvers\FieldResolverInterface;
use GraphQLByPoP\GraphQLServer\TypeDataLoaders\QueryRootTypeDataLoader;
class QueryRootTypeResolver extends \GraphQLByPoP\GraphQLServer\TypeResolvers\AbstractUseRootAsSourceForSchemaTypeResolver
{
    use ReservedNameTypeResolverTrait;
    public const NAME = 'QueryRoot';
    public function getTypeName() : string
    {
        return self::NAME;
    }
    public function getSchemaTypeDescription() : ?string
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        return $translationAPI->__('Query type, starting from which the query is executed', 'graphql-server');
    }
    /**
     * @param object $resultItem
     */
    public function getID($resultItem)
    {
        /** @var QueryRoot */
        $queryRoot = $resultItem;
        return $queryRoot->getID();
    }
    public function getTypeDataLoaderClass() : string
    {
        return \GraphQLByPoP\GraphQLServer\TypeDataLoaders\QueryRootTypeDataLoader::class;
    }
    protected function isFieldNameConditionSatisfiedForSchema(\PoP\ComponentModel\FieldResolvers\FieldResolverInterface $fieldResolver, string $fieldName) : bool
    {
        return !\in_array($fieldName, ['mutationRoot']) && $fieldResolver->resolveFieldMutationResolverClass($this, $fieldName) === null;
    }
}
