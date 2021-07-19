<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PrefixedByPoP\Symfony\Component\DependencyInjection\Attribute;

/**
 * An attribute to tell how a base type should be tagged.
 *
 * @author Nicolas Grekas <p@tchwork.com>
 * @annotation
 */
class AutoconfigureTag extends Autoconfigure
{
    public function __construct(string $name = null, array $attributes = [])
    {
        parent::__construct([[$name ?? 0 => $attributes]], null, null, null, null, null, null, null);
    }
}
