<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PrefixedByPoP\Symfony\Component\HttpFoundation\RequestMatcher;

use PrefixedByPoP\Symfony\Component\HttpFoundation\Request;
use PrefixedByPoP\Symfony\Component\HttpFoundation\RequestMatcherInterface;
/**
 * Checks the HTTP port of a Request.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class PortRequestMatcher implements RequestMatcherInterface
{
    /**
     * @var int
     */
    private $port;
    public function __construct(int $port)
    {
        $this->port = $port;
    }
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function matches($request) : bool
    {
        return $request->getPort() === $this->port;
    }
}
