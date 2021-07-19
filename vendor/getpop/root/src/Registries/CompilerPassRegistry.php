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
    public function addCompilerPass(CompilerPassInterface $compilerPass) : void
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
