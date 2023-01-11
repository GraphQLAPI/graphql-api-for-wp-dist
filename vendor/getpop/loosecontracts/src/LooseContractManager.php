<?php

declare (strict_types=1);
namespace PoP\LooseContracts;

class LooseContractManager implements \PoP\LooseContracts\LooseContractManagerInterface
{
    /**
     * @var string[]
     */
    protected $requiredNames = [];
    /**
     * @var string[]
     */
    protected $implementedNames = [];
    /**
     * @return string[]
     */
    public function getNotImplementedRequiredNames() : array
    {
        return \array_diff($this->requiredNames, $this->implementedNames);
    }
    /**
     * @param string[] $names
     */
    public function requireNames($names) : void
    {
        $this->requiredNames = \array_merge($this->requiredNames, $names);
    }
    /**
     * @param string[] $names
     */
    public function implementNames($names) : void
    {
        $this->implementedNames = \array_merge($this->implementedNames, $names);
    }
}
