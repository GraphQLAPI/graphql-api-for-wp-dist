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
 * Checks the Request content is valid JSON.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class IsJsonRequestMatcher implements RequestMatcherInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function matches($request) : bool
    {
        try {
            \json_decode($request->getContent(), \true, 512, \JSON_BIGINT_AS_STRING);
        } catch (\JsonException $exception) {
            return \false;
        }
        return \true;
    }
}
