<?php

declare (strict_types=1);
namespace PoP\Definitions;

interface DefinitionManagerInterface
{
    /**
     * @return array<string, DefinitionResolverInterface>
     */
    public function getDefinitionResolvers() : array;
    public function getDefinitionResolver(string $group) : ?\PoP\Definitions\DefinitionResolverInterface;
    public function setDefinitionResolver(\PoP\Definitions\DefinitionResolverInterface $definition_resolver, string $group) : void;
    public function setDefinitionPersistence(\PoP\Definitions\DefinitionPersistenceInterface $definition_persistence) : void;
    public function getDefinitionPersistence() : ?\PoP\Definitions\DefinitionPersistenceInterface;
    public function getUniqueDefinition(string $name, string $group) : string;
    public function getDefinition(string $name, string $group) : string;
    public function getOriginalName(string $definition, string $group) : string;
    public function maybeStoreDefinitionsPersistently() : void;
}
