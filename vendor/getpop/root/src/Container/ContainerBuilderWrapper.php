<?php

declare (strict_types=1);
namespace PoP\Root\Container;

use PrefixedByPoP\Symfony\Component\DependencyInjection\ContainerBuilder;
use PrefixedByPoP\Symfony\Component\DependencyInjection\Definition;
final class ContainerBuilderWrapper implements \PoP\Root\Container\ContainerBuilderWrapperInterface
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerBuilder
     */
    private $containerBuilder;
    public final function __construct(ContainerBuilder $containerBuilder)
    {
        $this->containerBuilder = $containerBuilder;
    }
    public final function getContainerBuilder() : ContainerBuilder
    {
        return $this->containerBuilder;
    }
    public final function getDefinition(string $id) : Definition
    {
        return $this->containerBuilder->getDefinition($id);
    }
    /**
     * @return Definition[] An array of Definition instances
     */
    public final function getDefinitions() : array
    {
        return $this->containerBuilder->getDefinitions();
    }
}
