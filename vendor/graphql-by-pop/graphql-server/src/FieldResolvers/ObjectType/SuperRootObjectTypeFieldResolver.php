<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\FieldResolvers\ObjectType;

use GraphQLByPoP\GraphQLServer\ObjectModels\MutationRoot;
use GraphQLByPoP\GraphQLServer\ObjectModels\QueryRoot;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\MutationRootObjectTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\QueryRootObjectTypeResolver;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\ObjectModels\Root;
use PoP\Engine\ObjectModels\SuperRoot;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\Engine\TypeResolvers\ObjectType\SuperRootObjectTypeResolver;
class SuperRootObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    /**
     * @var \PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver|null
     */
    private $rootObjectTypeResolver;
    /**
     * @var \GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\QueryRootObjectTypeResolver|null
     */
    private $queryRootObjectTypeResolver;
    /**
     * @var \GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\MutationRootObjectTypeResolver|null
     */
    private $mutationRootObjectTypeResolver;
    /**
     * @param \PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver $rootObjectTypeResolver
     */
    public final function setRootObjectTypeResolver($rootObjectTypeResolver) : void
    {
        $this->rootObjectTypeResolver = $rootObjectTypeResolver;
    }
    protected final function getRootObjectTypeResolver() : RootObjectTypeResolver
    {
        /** @var RootObjectTypeResolver */
        return $this->rootObjectTypeResolver = $this->rootObjectTypeResolver ?? $this->instanceManager->getInstance(RootObjectTypeResolver::class);
    }
    /**
     * @param \GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\QueryRootObjectTypeResolver $queryRootObjectTypeResolver
     */
    public final function setQueryRootObjectTypeResolver($queryRootObjectTypeResolver) : void
    {
        $this->queryRootObjectTypeResolver = $queryRootObjectTypeResolver;
    }
    protected final function getQueryRootObjectTypeResolver() : QueryRootObjectTypeResolver
    {
        /** @var QueryRootObjectTypeResolver */
        return $this->queryRootObjectTypeResolver = $this->queryRootObjectTypeResolver ?? $this->instanceManager->getInstance(QueryRootObjectTypeResolver::class);
    }
    /**
     * @param \GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\MutationRootObjectTypeResolver $mutationRootObjectTypeResolver
     */
    public final function setMutationRootObjectTypeResolver($mutationRootObjectTypeResolver) : void
    {
        $this->mutationRootObjectTypeResolver = $mutationRootObjectTypeResolver;
    }
    protected final function getMutationRootObjectTypeResolver() : MutationRootObjectTypeResolver
    {
        /** @var MutationRootObjectTypeResolver */
        return $this->mutationRootObjectTypeResolver = $this->mutationRootObjectTypeResolver ?? $this->instanceManager->getInstance(MutationRootObjectTypeResolver::class);
    }
    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo() : array
    {
        return [SuperRootObjectTypeResolver::class];
    }
    public function getFieldNamesToResolve() : array
    {
        return [
            /**
             * Have 2 fields to retrieve the Root when Nested Mutations
             * are enabled (instead of a single one `_root`) because then
             * we can define Access Control validations on the `query`
             * or `mutation` operation:
             *
             * The corresponding `@validate...` directives will be added
             * to either field `_rootForQueryRoot` or `_rootForMutationRoot`
             * on the SuperRoot object.
             */
            '_rootForQueryRoot',
            '_rootForMutationRoot',
            '_queryRoot',
            '_mutationRoot',
        ];
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldDescription($objectTypeResolver, $fieldName) : ?string
    {
        switch ($fieldName) {
            case '_rootForQueryRoot':
                return $this->__('Get the Root type (as requested by a query operation)', 'engine');
            case '_rootFoMutationRoot':
                return $this->__('Get the Root type (as requested by a mutation operation)', 'engine');
            case '_queryRoot':
                return $this->__('Get the Query Root type', 'engine');
            case '_mutationRoot':
                return $this->__('Get the Mutation Root type', 'engine');
            default:
                return parent::getFieldDescription($objectTypeResolver, $fieldName);
        }
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldTypeResolver($objectTypeResolver, $fieldName) : ConcreteTypeResolverInterface
    {
        switch ($fieldName) {
            case '_rootForQueryRoot':
            case '_rootForMutationRoot':
                return $this->getRootObjectTypeResolver();
            case '_queryRoot':
                return $this->getQueryRootObjectTypeResolver();
            case '_mutationRoot':
                return $this->getMutationRootObjectTypeResolver();
            default:
                return parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
        }
    }
    /**
     * @return mixed
     * @param object $object
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
     */
    public function resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore)
    {
        /** @var SuperRoot */
        $superRoot = $object;
        switch ($fieldDataAccessor->getFieldName()) {
            case '_rootForQueryRoot':
            case '_rootForMutationRoot':
                return Root::ID;
            case '_queryRoot':
                return QueryRoot::ID;
            case '_mutationRoot':
                return MutationRoot::ID;
            default:
                return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
        }
    }
}
