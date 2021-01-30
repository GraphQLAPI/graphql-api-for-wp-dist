<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\TypeResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use GraphQLByPoP\GraphQLServer\ObjectModels\MutationRoot;
use PoP\Engine\TypeResolvers\ReservedNameTypeResolverTrait;
use PoP\ComponentModel\FieldResolvers\FieldResolverInterface;
use GraphQLByPoP\GraphQLServer\TypeDataLoaders\MutationRootTypeDataLoader;
class MutationRootTypeResolver extends \GraphQLByPoP\GraphQLServer\TypeResolvers\AbstractUseRootAsSourceForSchemaTypeResolver
{
    use ReservedNameTypeResolverTrait;
    public const NAME = 'MutationRoot';
    public function getTypeName() : string
    {
        return self::NAME;
    }
    public function getSchemaTypeDescription() : ?string
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        return $translationAPI->__('Mutation type, starting from which mutations are executed', 'graphql-server');
    }
    /**
     * @param object $resultItem
     */
    public function getID($resultItem)
    {
        /** @var MutationRoot */
        $mutationRoot = $resultItem;
        return $mutationRoot->getID();
    }
    public function getTypeDataLoaderClass() : string
    {
        return \GraphQLByPoP\GraphQLServer\TypeDataLoaders\MutationRootTypeDataLoader::class;
    }
    protected function isFieldNameConditionSatisfiedForSchema(\PoP\ComponentModel\FieldResolvers\FieldResolverInterface $fieldResolver, string $fieldName) : bool
    {
        return \in_array($fieldName, ['id', 'self', '__typename']) || $fieldResolver->resolveFieldMutationResolverClass($this, $fieldName) !== null;
    }
}
