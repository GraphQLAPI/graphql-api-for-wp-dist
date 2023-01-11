<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\ComponentProcessors;

use PoP\Engine\ObjectModels\SuperRoot;
use PoP\Engine\TypeResolvers\ObjectType\SuperRootObjectTypeResolver;
use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\Root\App;
class SuperRootGraphQLRelationalFieldDataloadComponentProcessor extends \GraphQLByPoP\GraphQLServer\ComponentProcessors\AbstractGraphQLRelationalFieldDataloadComponentProcessor
{
    public const COMPONENT_DATALOAD_RELATIONALFIELDS_SUPERROOT = 'dataload-relationalfields-superroot';
    /**
     * @var \PoP\Engine\TypeResolvers\ObjectType\SuperRootObjectTypeResolver|null
     */
    private $superRootObjectTypeResolver;
    /**
     * @param \PoP\Engine\TypeResolvers\ObjectType\SuperRootObjectTypeResolver $superRootObjectTypeResolver
     */
    public final function setSuperRootObjectTypeResolver($superRootObjectTypeResolver) : void
    {
        $this->superRootObjectTypeResolver = $superRootObjectTypeResolver;
    }
    protected final function getSuperRootObjectTypeResolver() : SuperRootObjectTypeResolver
    {
        /** @var SuperRootObjectTypeResolver */
        return $this->superRootObjectTypeResolver = $this->superRootObjectTypeResolver ?? $this->instanceManager->getInstance(SuperRootObjectTypeResolver::class);
    }
    /**
     * @return string[]
     */
    public function getComponentNamesToProcess() : array
    {
        return array(self::COMPONENT_DATALOAD_RELATIONALFIELDS_SUPERROOT);
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
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_SUPERROOT:
                return SuperRoot::ID;
        }
        return parent::getObjectIDOrIDs($component, $props, $data_properties);
    }
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getRelationalTypeResolver($component) : ?RelationalTypeResolverInterface
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_SUPERROOT:
                return $this->getSuperRootObjectTypeResolver();
        }
        return parent::getRelationalTypeResolver($component);
    }
}
