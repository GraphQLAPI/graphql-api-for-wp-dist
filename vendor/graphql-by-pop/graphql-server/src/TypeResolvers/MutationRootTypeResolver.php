<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\TypeResolvers;

use GraphQLByPoP\GraphQLServer\ObjectModels\MutationRoot;
use PoP\Engine\TypeResolvers\ReservedNameTypeResolverTrait;
use PoP\ComponentModel\FieldResolvers\FieldResolverInterface;
use GraphQLByPoP\GraphQLServer\TypeDataLoaders\MutationRootTypeDataLoader;
class MutationRootTypeResolver extends \GraphQLByPoP\GraphQLServer\TypeResolvers\AbstractUseRootAsSourceForSchemaTypeResolver
{
    use ReservedNameTypeResolverTrait;
    public function getTypeName() : string
    {
        return 'MutationRoot';
    }
    public function getSchemaTypeDescription() : ?string
    {
        return $this->translationAPI->__('Mutation type, starting from which mutations are executed', 'graphql-server');
    }
    /**
     * @return string|int|null
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
        return MutationRootTypeDataLoader::class;
    }
    protected function isFieldNameConditionSatisfiedForSchema(FieldResolverInterface $fieldResolver, string $fieldName) : bool
    {
        return \in_array($fieldName, ['id', 'self', '__typename']) || $fieldResolver->resolveFieldMutationResolverClass($this, $fieldName) !== null;
    }
}
