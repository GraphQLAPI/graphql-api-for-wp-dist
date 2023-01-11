<?php

declare (strict_types=1);
namespace PoP\Root\Registries;

use PrefixedByPoP\Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
interface CompilerPassRegistryInterface
{
    /**
     * @param \Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface $compilerPass
     */
    public function addCompilerPass($compilerPass) : void;
    /**
     * @return CompilerPassInterface[]
     */
    public function getCompilerPasses() : array;
}
