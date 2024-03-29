<?php

/*
 * This file is part of the Cortex package.
 *
 * (c) Giuseppe Mazzapica <giuseppe.mazzapica@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PrefixedByPoP\Brain\Cortex\Router;

use PrefixedByPoP\Brain\Cortex\Uri\UriInterface;
/**
 * @author  Giuseppe Mazzapica <giuseppe.mazzapica@gmail.com>
 * @license http://opensource.org/licenses/MIT MIT
 * @package Cortex
 */
interface RouterInterface
{
    /**
     * @param  \Brain\Cortex\Uri\UriInterface $uri
     * @param  string                         $httpMethod
     * @return \Brain\Cortex\Router\MatchingResult
     */
    public function match($uri, $httpMethod);
}
