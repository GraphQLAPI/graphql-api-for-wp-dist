<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PrefixedByPoP\Symfony\Component\DependencyInjection\Extension;

use PrefixedByPoP\Symfony\Component\Config\Definition\Configuration;
use PrefixedByPoP\Symfony\Component\Config\Definition\ConfigurationInterface;
use PrefixedByPoP\Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use PrefixedByPoP\Symfony\Component\DependencyInjection\ContainerBuilder;
use PrefixedByPoP\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
/**
 * An Extension that provides configuration hooks.
 *
 * @author Yonel Ceruto <yonelceruto@gmail.com>
 */
abstract class AbstractExtension extends Extension implements ConfigurableExtensionInterface, PrependExtensionInterface
{
    use ExtensionTrait;
    /**
     * @param \Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator $definition
     */
    public function configure($definition) : void
    {
    }
    /**
     * @param \Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $container
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $builder
     */
    public function prependExtension($container, $builder) : void
    {
    }
    /**
     * @param mixed[] $config
     * @param \Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $container
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $builder
     */
    public function loadExtension($config, $container, $builder) : void
    {
    }
    /**
     * @param mixed[] $config
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function getConfiguration($config, $container) : ?ConfigurationInterface
    {
        return new Configuration($this, $container, $this->getAlias());
    }
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public final function prepend($container) : void
    {
        $callback = function (ContainerConfigurator $configurator) use($container) {
            $this->prependExtension($configurator, $container);
        };
        $this->executeConfiguratorCallback($container, $callback, $this);
    }
    /**
     * @param mixed[] $configs
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public final function load($configs, $container) : void
    {
        $config = $this->processConfiguration($this->getConfiguration([], $container), $configs);
        $callback = function (ContainerConfigurator $configurator) use($config, $container) {
            $this->loadExtension($config, $configurator, $container);
        };
        $this->executeConfiguratorCallback($container, $callback, $this);
    }
}
