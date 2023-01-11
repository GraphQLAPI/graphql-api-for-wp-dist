<?php

declare (strict_types=1);
namespace PoP\Root\Container\CompilerPasses;

use PoP\Root\Container\ContainerBuilderWrapper;
use PoP\Root\Container\ContainerBuilderWrapperInterface;
use PrefixedByPoP\Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use PrefixedByPoP\Symfony\Component\DependencyInjection\ContainerBuilder;
use PrefixedByPoP\Symfony\Component\DependencyInjection\Reference;
use PrefixedByPoP\Symfony\Component\ExpressionLanguage\Expression;
/**
 * This class enables to leak the implementation of Compiler Passes to the application.
 * This is needed to add compiler passes on "-wp" packages, which are not scoped
 * with PHP-Scoper. Then, in these packages we can't reference Symfony (or any 3rd party)
 */
abstract class AbstractCompilerPass implements CompilerPassInterface
{
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $containerBuilder
     */
    public final function process($containerBuilder) : void
    {
        if (!$this->enabled()) {
            return;
        }
        $this->doProcess(new ContainerBuilderWrapper($containerBuilder));
    }
    /**
     * Compiler passes must implement the logic in this function, not in `process`
     * @param \PoP\Root\Container\ContainerBuilderWrapperInterface $containerBuilderWrapper
     */
    protected abstract function doProcess($containerBuilderWrapper) : void;
    protected function enabled() : bool
    {
        return \true;
    }
    /**
     * @param string $id
     */
    protected function createReference($id) : Reference
    {
        return new Reference($id);
    }
    /**
     * @param string $expression
     */
    protected function createExpression($expression) : Expression
    {
        return new Expression($expression);
    }
}
