<?php

declare (strict_types=1);
namespace PoP\Root\Registries;

use PrefixedByPoP\Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
interface CompilerPassRegistryInterface
{
    public function addCompilerPass(CompilerPassInterface $compilerPass) : void;
    /**
     * @return CompilerPassInterface[]
     */
    public function getCompilerPasses() : array;
}
