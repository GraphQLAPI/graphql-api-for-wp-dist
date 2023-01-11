<?php

declare (strict_types=1);
namespace PoP\Definitions;

interface DefinitionManagerInterface
{
    /**
     * @return array<string,DefinitionResolverInterface>
     */
    public function getDefinitionResolvers() : array;
    /**
     * @param string $group
     */
    public function getDefinitionResolver($group) : ?\PoP\Definitions\DefinitionResolverInterface;
    /**
     * @param \PoP\Definitions\DefinitionResolverInterface $definition_resolver
     * @param string $group
     */
    public function setDefinitionResolver($definition_resolver, $group) : void;
    /**
     * @param \PoP\Definitions\DefinitionPersistenceInterface $definition_persistence
     */
    public function setDefinitionPersistence($definition_persistence) : void;
    public function getDefinitionPersistence() : ?\PoP\Definitions\DefinitionPersistenceInterface;
    /**
     * @param string $name
     * @param string $group
     */
    public function getDefinition($name, $group) : string;
    /**
     * @param string $definition
     * @param string $group
     */
    public function getOriginalName($definition, $group) : string;
    public function maybeStoreDefinitionsPersistently() : void;
}
