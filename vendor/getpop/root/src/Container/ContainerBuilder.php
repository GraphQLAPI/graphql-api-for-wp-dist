<?php

declare (strict_types=1);
namespace PoP\Root\Container;

use PrefixedByPoP\Symfony\Component\DependencyInjection\ContainerBuilder as UpstreamContainerBuilder;
class ContainerBuilder extends UpstreamContainerBuilder implements \PoP\Root\Container\ContainerInterface
{
}
