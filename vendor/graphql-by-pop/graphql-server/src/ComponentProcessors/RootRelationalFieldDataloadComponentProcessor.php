<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoP\Root\App;
use GraphQLByPoP\GraphQLServer\ObjectModels\MutationRoot;
use GraphQLByPoP\GraphQLServer\ObjectModels\QueryRoot;
use GraphQLByPoP\GraphQLServer\Schema\GraphQLSchemaDefinitionServiceInterface;
use PoPAPI\API\ComponentProcessors\AbstractRelationalFieldDataloadComponentProcessor;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
class RootRelationalFieldDataloadComponentProcessor extends AbstractRelationalFieldDataloadComponentProcessor
{
    public const COMPONENT_DATALOAD_RELATIONALFIELDS_QUERYROOT = 'dataload-relationalfields-queryroot';
    public const COMPONENT_DATALOAD_RELATIONALFIELDS_MUTATIONROOT = 'dataload-relationalfields-mutationroot';
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
    /**
     * @return string[]
     */
    public function getComponentNamesToProcess() : array
    {
        return array(self::COMPONENT_DATALOAD_RELATIONALFIELDS_QUERYROOT, self::COMPONENT_DATALOAD_RELATIONALFIELDS_MUTATIONROOT);
    }
    /**
     * @return string|int|array<string|int>|null
     * @param array<string,mixed> $props
     * @param array<string,mixed> $data_properties
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getObjectIDOrIDs($component, &$props, &$data_properties)
    {
        if (App::getState('does-api-query-have-errors')) {
            return null;
        }
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_QUERYROOT:
                return QueryRoot::ID;
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_MUTATIONROOT:
                return MutationRoot::ID;
        }
        return parent::getObjectIDOrIDs($component, $props, $data_properties);
    }
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getRelationalTypeResolver($component) : ?RelationalTypeResolverInterface
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_QUERYROOT:
                return $this->getGraphQLSchemaDefinitionService()->getSchemaQueryRootObjectTypeResolver();
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_MUTATIONROOT:
                return $this->getGraphQLSchemaDefinitionService()->getSchemaMutationRootObjectTypeResolver();
        }
        return parent::getRelationalTypeResolver($component);
    }
}
