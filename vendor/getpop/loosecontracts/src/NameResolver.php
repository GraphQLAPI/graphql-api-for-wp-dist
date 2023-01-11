<?php

declare (strict_types=1);
namespace PoP\LooseContracts;

class NameResolver extends \PoP\LooseContracts\AbstractNameResolver
{
    /**
     * @var string[]
     */
    protected $names = [];
    /**
     * @param string $name
     */
    public function getName($name) : string
    {
        // If there's no entry, then use the original $hookName
        return $this->names[$name] ?? $name;
    }
    /**
     * @param string $abstractName
     * @param string $implementationName
     */
    public function implementName($abstractName, $implementationName) : void
    {
        parent::implementName($abstractName, $implementationName);
        $this->names[$abstractName] = $implementationName;
    }
    /**
     * @param string[] $names
     */
    public function implementNames($names) : void
    {
        parent::implementNames($names);
        $this->names = \array_merge($this->names, $names);
    }
}
