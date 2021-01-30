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

use PrefixedByPoP\Symfony\Component\DependencyInjection\ChildDefinition;
use PrefixedByPoP\Symfony\Component\DependencyInjection\ContainerBuilder;
use PrefixedByPoP\Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
/**
 * @author Nicolas Grekas <p@tchwork.com>
 */
class ResolveClassPass implements \PrefixedByPoP\Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(\PrefixedByPoP\Symfony\Component\DependencyInjection\ContainerBuilder $container)
    {
        foreach ($container->getDefinitions() as $id => $definition) {
            if ($definition->isSynthetic() || null !== $definition->getClass()) {
                continue;
            }
            if (\preg_match('/^[a-zA-Z_\\x7f-\\xff][a-zA-Z0-9_\\x7f-\\xff]*+(?:\\\\[a-zA-Z_\\x7f-\\xff][a-zA-Z0-9_\\x7f-\\xff]*+)++$/', $id)) {
                if ($definition instanceof \PrefixedByPoP\Symfony\Component\DependencyInjection\ChildDefinition && !\class_exists($id)) {
                    throw new \PrefixedByPoP\Symfony\Component\DependencyInjection\Exception\InvalidArgumentException(\sprintf('Service definition "%s" has a parent but no class, and its name looks like a FQCN. Either the class is missing or you want to inherit it from the parent service. To resolve this ambiguity, please rename this service to a non-FQCN (e.g. using dots), or create the missing class.', $id));
                }
                $definition->setClass($id);
            }
        }
    }
}
