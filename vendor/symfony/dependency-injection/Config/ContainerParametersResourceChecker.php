<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PrefixedByPoP\Symfony\Component\DependencyInjection\Config;

use PrefixedByPoP\Symfony\Component\Config\Resource\ResourceInterface;
use PrefixedByPoP\Symfony\Component\Config\ResourceCheckerInterface;
use PrefixedByPoP\Symfony\Component\DependencyInjection\ContainerInterface;
/**
 * @author Maxime Steinhausser <maxime.steinhausser@gmail.com>
 */
class ContainerParametersResourceChecker implements ResourceCheckerInterface
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private $container;
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    /**
     * @param \Symfony\Component\Config\Resource\ResourceInterface $metadata
     */
    public function supports($metadata) : bool
    {
        return $metadata instanceof ContainerParametersResource;
    }
    /**
     * @param \Symfony\Component\Config\Resource\ResourceInterface $resource
     * @param int $timestamp
     */
    public function isFresh($resource, $timestamp) : bool
    {
        foreach ($resource->getParameters() as $key => $value) {
            if (!$this->container->hasParameter($key) || $this->container->getParameter($key) !== $value) {
                return \false;
            }
        }
        return \true;
    }
}
