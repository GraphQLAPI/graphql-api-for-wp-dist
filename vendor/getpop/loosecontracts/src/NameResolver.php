<?php

declare (strict_types=1);
namespace PoP\LooseContracts;

class NameResolver extends \PoP\LooseContracts\AbstractNameResolver
{
    /**
     * @var string[]
     */
    protected $names = [];
    public function getName(string $name) : string
    {
        // If there's no entry, then use the original $hookName
        return $this->names[$name] ?? $name;
    }
    public function implementName(string $abstractName, string $implementationName) : void
    {
        parent::implementName($abstractName, $implementationName);
        $this->names[$abstractName] = $implementationName;
    }
    /**
     * @param string[] $names
     */
    public function implementNames(array $names) : void
    {
        parent::implementNames($names);
        $this->names = \array_merge($this->names, $names);
    }
}
