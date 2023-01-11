<?php

declare (strict_types=1);
namespace PoP\Definitions;

interface DefinitionResolverInterface
{
    /**
     * @param string $name
     * @param string $group
     */
    public function getDefinition($name, $group) : string;
    /**
     * @return array<string,mixed>
     */
    public function getDataToPersist() : array;
    /**
     * Allow Persistent Definitions to set a different value
     *
     * @param array<string,mixed> $persisted_data
     */
    public function setPersistedData($persisted_data) : void;
}
