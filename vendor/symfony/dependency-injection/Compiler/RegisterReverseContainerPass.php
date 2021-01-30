<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PrefixedByPoP\Symfony\Component\DependencyInjection\Compiler;

use PrefixedByPoP\Symfony\Component\DependencyInjection\Argument\ServiceClosureArgument;
use PrefixedByPoP\Symfony\Component\DependencyInjection\ContainerBuilder;
use PrefixedByPoP\Symfony\Component\DependencyInjection\ContainerInterface;
use PrefixedByPoP\Symfony\Component\DependencyInjection\Definition;
use PrefixedByPoP\Symfony\Component\DependencyInjection\Reference;
/**
 * @author Nicolas Grekas <p@tchwork.com>
 */
class RegisterReverseContainerPass implements \PrefixedByPoP\Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface
{
    private $beforeRemoving;
    private $serviceId;
    private $tagName;
    public function __construct(bool $beforeRemoving, string $serviceId = 'reverse_container', string $tagName = 'container.reversible')
    {
        $this->beforeRemoving = $beforeRemoving;
        $this->serviceId = $serviceId;
        $this->tagName = $tagName;
    }
    public function process(\PrefixedByPoP\Symfony\Component\DependencyInjection\ContainerBuilder $container)
    {
        if (!$container->hasDefinition($this->serviceId)) {
            return;
        }
        $refType = $this->beforeRemoving ? \PrefixedByPoP\Symfony\Component\DependencyInjection\ContainerInterface::IGNORE_ON_UNINITIALIZED_REFERENCE : \PrefixedByPoP\Symfony\Component\DependencyInjection\ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE;
        $services = [];
        foreach ($container->findTaggedServiceIds($this->tagName) as $id => $tags) {
            $services[$id] = new \PrefixedByPoP\Symfony\Component\DependencyInjection\Reference($id, $refType);
        }
        if ($this->beforeRemoving) {
            // prevent inlining of the reverse container
            $services[$this->serviceId] = new \PrefixedByPoP\Symfony\Component\DependencyInjection\Reference($this->serviceId, $refType);
        }
        $locator = $container->getDefinition($this->serviceId)->getArgument(1);
        if ($locator instanceof \PrefixedByPoP\Symfony\Component\DependencyInjection\Reference) {
            $locator = $container->getDefinition((string) $locator);
        }
        if ($locator instanceof \PrefixedByPoP\Symfony\Component\DependencyInjection\Definition) {
            foreach ($services as $id => $ref) {
                $services[$id] = new \PrefixedByPoP\Symfony\Component\DependencyInjection\Argument\ServiceClosureArgument($ref);
            }
            $locator->replaceArgument(0, $services);
        } else {
            $locator->setValues($services);
        }
    }
}
