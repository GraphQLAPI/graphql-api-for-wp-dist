<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PrefixedByPoP\Symfony\Component\PropertyInfo\DependencyInjection;

use PrefixedByPoP\Symfony\Component\DependencyInjection\Argument\IteratorArgument;
use PrefixedByPoP\Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use PrefixedByPoP\Symfony\Component\DependencyInjection\Compiler\PriorityTaggedServiceTrait;
use PrefixedByPoP\Symfony\Component\DependencyInjection\ContainerBuilder;
/**
 * Adds extractors to the property_info.constructor_extractor service.
 *
 * @author Dmitrii Poddubnyi <dpoddubny@gmail.com>
 */
final class PropertyInfoConstructorPass implements \PrefixedByPoP\Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface
{
    use PriorityTaggedServiceTrait;
    private $service;
    private $tag;
    public function __construct(string $service = 'property_info.constructor_extractor', string $tag = 'property_info.constructor_extractor')
    {
        $this->service = $service;
        $this->tag = $tag;
    }
    /**
     * {@inheritdoc}
     */
    public function process(\PrefixedByPoP\Symfony\Component\DependencyInjection\ContainerBuilder $container)
    {
        if (!$container->hasDefinition($this->service)) {
            return;
        }
        $definition = $container->getDefinition($this->service);
        $listExtractors = $this->findAndSortTaggedServices($this->tag, $container);
        $definition->replaceArgument(0, new \PrefixedByPoP\Symfony\Component\DependencyInjection\Argument\IteratorArgument($listExtractors));
    }
}
