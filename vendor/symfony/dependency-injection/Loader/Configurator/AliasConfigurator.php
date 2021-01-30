<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PrefixedByPoP\Symfony\Component\DependencyInjection\Loader\Configurator;

use PrefixedByPoP\Symfony\Component\DependencyInjection\Alias;
/**
 * @author Nicolas Grekas <p@tchwork.com>
 */
class AliasConfigurator extends \PrefixedByPoP\Symfony\Component\DependencyInjection\Loader\Configurator\AbstractServiceConfigurator
{
    public const FACTORY = 'alias';
    use Traits\DeprecateTrait;
    use Traits\PublicTrait;
    public function __construct(\PrefixedByPoP\Symfony\Component\DependencyInjection\Loader\Configurator\ServicesConfigurator $parent, \PrefixedByPoP\Symfony\Component\DependencyInjection\Alias $alias)
    {
        $this->parent = $parent;
        $this->definition = $alias;
    }
}
