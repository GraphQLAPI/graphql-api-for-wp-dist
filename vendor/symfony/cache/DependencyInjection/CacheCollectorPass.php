<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PrefixedByPoP\Symfony\Component\Cache\DependencyInjection;

use PrefixedByPoP\Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;
use PrefixedByPoP\Symfony\Component\Cache\Adapter\TraceableAdapter;
use PrefixedByPoP\Symfony\Component\Cache\Adapter\TraceableTagAwareAdapter;
use PrefixedByPoP\Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use PrefixedByPoP\Symfony\Component\DependencyInjection\ContainerBuilder;
use PrefixedByPoP\Symfony\Component\DependencyInjection\Definition;
use PrefixedByPoP\Symfony\Component\DependencyInjection\Reference;
/**
 * Inject a data collector to all the cache services to be able to get detailed statistics.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class CacheCollectorPass implements CompilerPassInterface
{
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function process($container)
    {
        if (!$container->hasDefinition('data_collector.cache')) {
            return;
        }
        foreach ($container->findTaggedServiceIds('cache.pool') as $id => $attributes) {
            $poolName = $attributes[0]['name'] ?? $id;
            $this->addToCollector($id, $poolName, $container);
        }
    }
    private function addToCollector(string $id, string $name, ContainerBuilder $container)
    {
        $definition = $container->getDefinition($id);
        if ($definition->isAbstract()) {
            return;
        }
        $collectorDefinition = $container->getDefinition('data_collector.cache');
        $recorder = new Definition(\is_subclass_of($definition->getClass(), TagAwareAdapterInterface::class) ? TraceableTagAwareAdapter::class : TraceableAdapter::class);
        $recorder->setTags($definition->getTags());
        if (!$definition->isPublic() || !$definition->isPrivate()) {
            $recorder->setPublic($definition->isPublic());
        }
        $recorder->setArguments([new Reference($innerId = $id . '.recorder_inner')]);
        foreach ($definition->getMethodCalls() as [$method, $args]) {
            if ('setCallbackWrapper' !== $method || !$args[0] instanceof Definition || !($args[0]->getArguments()[2] ?? null) instanceof Definition) {
                continue;
            }
            if ([new Reference($id), 'setCallbackWrapper'] == $args[0]->getArguments()[2]->getFactory()) {
                $args[0]->getArguments()[2]->setFactory([new Reference($innerId), 'setCallbackWrapper']);
            }
        }
        $definition->setTags([]);
        $definition->setPublic(\false);
        $container->setDefinition($innerId, $definition);
        $container->setDefinition($id, $recorder);
        // Tell the collector to add the new instance
        $collectorDefinition->addMethodCall('addInstance', [$name, new Reference($id)]);
        $collectorDefinition->setPublic(\false);
    }
}
