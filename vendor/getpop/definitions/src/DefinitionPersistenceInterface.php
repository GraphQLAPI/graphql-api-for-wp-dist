<?php

declare (strict_types=1);
namespace PoP\Definitions;

interface DefinitionPersistenceInterface
{
    /**
     * @return array<string,DefinitionResolverInterface>
     */
    public function getDefinitionResolvers() : array;
    public function storeDefinitionsPersistently() : void;
    /**
     * @param string $name
     * @param string $group
     */
    public function getSavedDefinition($name, $group) : ?string;
    /**
     * @param string $definition
     * @param string $group
     */
    public function getOriginalName($definition, $group) : ?string;
    /**
     * @param string $definition
     * @param string $name
     * @param string $group
     */
    public function saveDefinition($definition, $name, $group) : void;
    /**
     * @param \PoP\Definitions\DefinitionResolverInterface $definition_resolver
     * @param string $group
     */
    public function setDefinitionResolver($definition_resolver, $group) : void;
}
