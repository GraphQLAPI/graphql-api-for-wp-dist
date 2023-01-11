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

use PrefixedByPoP\Symfony\Component\Config\Definition\ConfigurableInterface;
use PrefixedByPoP\Symfony\Component\DependencyInjection\ContainerBuilder;
use PrefixedByPoP\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
/**
 * @author Yonel Ceruto <yonelceruto@gmail.com>
 */
interface ConfigurableExtensionInterface extends ConfigurableInterface
{
    /**
     * Allows an extension to prepend the extension configurations.
     * @param \Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $container
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $builder
     */
    public function prependExtension($container, $builder) : void;
    /**
     * Loads a specific configuration.
     * @param mixed[] $config
     * @param \Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $container
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $builder
     */
    public function loadExtension($config, $container, $builder) : void;
}
