<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PrefixedByPoP\Symfony\Component\DependencyInjection\ParameterBag;

use PrefixedByPoP\Symfony\Component\DependencyInjection\Container;
/**
 * @author Nicolas Grekas <p@tchwork.com>
 */
class ContainerBag extends FrozenParameterBag implements ContainerBagInterface
{
    /**
     * @var \Symfony\Component\DependencyInjection\Container
     */
    private $container;
    public function __construct(Container $container)
    {
        $this->container = $container;
    }
    public function all() : array
    {
        return $this->container->getParameterBag()->all();
    }
    /**
     * @return mixed[]|bool|string|int|float|\UnitEnum|null
     * @param string $name
     */
    public function get($name)
    {
        return $this->container->getParameter($name);
    }
    /**
     * @param string $name
     */
    public function has($name) : bool
    {
        return $this->container->hasParameter($name);
    }
}
