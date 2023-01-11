<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\FieldResolvers\ObjectType;

use PoP\Root\App;
use GraphQLByPoP\GraphQLServer\Module;
use GraphQLByPoP\GraphQLServer\ModuleConfiguration;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\MutationRootObjectTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\QueryRootObjectTypeResolver;
use PoP\ComponentModel\Module as ComponentModelModule;
use PoP\ComponentModel\ModuleConfiguration as ComponentModelModuleConfiguration;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
/**
 * Add connections to the QueryRoot and MutationRoot types,
 * so they can be accessed to generate the schema
 */
class RegisterQueryAndMutationRootsRootObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    /**
     * @var \GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\QueryRootObjectTypeResolver|null
     */
    private $queryRootObjectTypeResolver;
    /**
     * @var \GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\MutationRootObjectTypeResolver|null
     */
    private $mutationRootObjectTypeResolver;
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
        return [RootObjectTypeResolver::class];
    }
    /**
     * Register the fields for the Standard GraphQL server only,
     * and when nested mutations are disabled, and when not additionally
     * appending the QueryRoot and Mutation Root to the schema
     *
     * @return string[]
     */
    public function getFieldNamesToResolve() : array
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if ($moduleConfiguration->enableNestedMutations() && !$moduleConfiguration->addConnectionFromRootToQueryRootAndMutationRoot()) {
            return [];
        }
        /** @var ComponentModelModuleConfiguration */
        $moduleConfiguration = App::getModule(ComponentModelModule::class)->getConfiguration();
        return \array_merge(['queryRoot'], $moduleConfiguration->enableMutations() ? ['mutationRoot'] : []);
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldDescription($objectTypeResolver, $fieldName) : ?string
    {
        switch ($fieldName) {
            case 'queryRoot':
                return $this->__('Get the Query Root type', 'graphql-server');
            case 'mutationRoot':
                return $this->__('Get the Mutation Root type', 'graphql-server');
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
            case 'queryRoot':
                return $this->getQueryRootObjectTypeResolver();
            case 'mutationRoot':
                return $this->getMutationRootObjectTypeResolver();
            default:
                return parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
        }
    }
}
