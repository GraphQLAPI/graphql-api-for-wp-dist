<?php

declare (strict_types=1);
namespace PoP\LooseContracts;

interface NameResolverInterface
{
    /**
     * @param string $name
     */
    public function getName($name) : string;
    /**
     * @param string $abstractName
     * @param string $implementationName
     */
    public function implementName($abstractName, $implementationName) : void;
    /**
     * @param string[] $names
     */
    public function implementNames($names) : void;
}
