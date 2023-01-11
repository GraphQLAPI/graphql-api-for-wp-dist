<?php

declare (strict_types=1);
namespace PoP\Root\Registries;

use PrefixedByPoP\Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
class CompilerPassRegistry implements \PoP\Root\Registries\CompilerPassRegistryInterface
{
    /**
     * @var CompilerPassInterface[]
     */
    protected $compilerPasses = [];
    /**
     * @param \Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface $compilerPass
     */
    public function addCompilerPass($compilerPass) : void
    {
        $this->compilerPasses[] = $compilerPass;
    }
    /**
     * @return CompilerPassInterface[]
     */
    public function getCompilerPasses() : array
    {
        return $this->compilerPasses;
    }
}
