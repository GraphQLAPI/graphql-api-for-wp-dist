<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\Hooks;

use GraphQLByPoP\GraphQLServer\Module;
use GraphQLByPoP\GraphQLServer\ModuleConfiguration;
use GraphQLByPoP\GraphQLServer\Schema\GraphQLSchemaDefinitionServiceInterface;
use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\FieldResolvers\ObjectType\ObjectTypeFieldResolverInterface;
use PoP\ComponentModel\TypeResolvers\HookHelpers;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
class NestedMutationHookSet extends AbstractHookSet
{
    /**
     * @var \GraphQLByPoP\GraphQLServer\Schema\GraphQLSchemaDefinitionServiceInterface|null
     */
    private $graphQLSchemaDefinitionService;
    /**
     * @param \GraphQLByPoP\GraphQLServer\Schema\GraphQLSchemaDefinitionServiceInterface $graphQLSchemaDefinitionService
     */
    public final function setGraphQLSchemaDefinitionService($graphQLSchemaDefinitionService) : void
    {
        $this->graphQLSchemaDefinitionService = $graphQLSchemaDefinitionService;
    }
    protected final function getGraphQLSchemaDefinitionService() : GraphQLSchemaDefinitionServiceInterface
    {
        /** @var GraphQLSchemaDefinitionServiceInterface */
        return $this->graphQLSchemaDefinitionService = $this->graphQLSchemaDefinitionService ?? $this->instanceManager->getInstance(GraphQLSchemaDefinitionServiceInterface::class);
    }
    protected function init() : void
    {
        App::addFilter(HookHelpers::getHookNameToFilterField(), \Closure::fromCallable([$this, 'maybeFilterFieldName']), 10, 4);
    }
    /**
     * For the standard GraphQL server:
     * If nested mutations are disabled, then remove registering fieldNames
     * when they have a MutationResolver for types other than the Root and MutationRoot
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface|\PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface $objectTypeOrInterfaceTypeResolver
     * @param \PoP\ComponentModel\FieldResolvers\ObjectType\ObjectTypeFieldResolverInterface|\PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface $objectTypeOrInterfaceTypeFieldResolver
     * @param bool $include
     * @param string $fieldName
     */
    public function maybeFilterFieldName($include, $objectTypeOrInterfaceTypeResolver, $objectTypeOrInterfaceTypeFieldResolver, $fieldName) : bool
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if ($moduleConfiguration->enableNestedMutations()) {
            return $include;
        }
        if ($objectTypeOrInterfaceTypeResolver instanceof InterfaceTypeResolverInterface) {
            return $include;
        }
        /** @var ObjectTypeResolverInterface */
        $objectTypeResolver = $objectTypeOrInterfaceTypeResolver;
        /** @var ObjectTypeFieldResolverInterface */
        $objectTypeFieldResolver = $objectTypeOrInterfaceTypeFieldResolver;
        if ($include && ($objectTypeResolver !== $this->getGraphQLSchemaDefinitionService()->getSchemaRootObjectTypeResolver() && $objectTypeResolver !== $this->getGraphQLSchemaDefinitionService()->getSchemaMutationRootObjectTypeResolver()) && $objectTypeFieldResolver->getFieldMutationResolver($objectTypeResolver, $fieldName) !== null) {
            return \false;
        }
        return $include;
    }
}
