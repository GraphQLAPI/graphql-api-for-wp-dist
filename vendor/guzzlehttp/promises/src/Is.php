<?php

namespace PrefixedByPoP\GuzzleHttp\Promise;

final class Is
{
    /**
     * Returns true if a promise is pending.
     *
     * @return bool
     */
    public static function pending(\PrefixedByPoP\GuzzleHttp\Promise\PromiseInterface $promise)
    {
        return $promise->getState() === \PrefixedByPoP\GuzzleHttp\Promise\PromiseInterface::PENDING;
    }
    /**
     * Returns true if a promise is fulfilled or rejected.
     *
     * @return bool
     */
    public static function settled(\PrefixedByPoP\GuzzleHttp\Promise\PromiseInterface $promise)
    {
        return $promise->getState() !== \PrefixedByPoP\GuzzleHttp\Promise\PromiseInterface::PENDING;
    }
    /**
     * Returns true if a promise is fulfilled.
     *
     * @return bool
     */
    public static function fulfilled(\PrefixedByPoP\GuzzleHttp\Promise\PromiseInterface $promise)
    {
        return $promise->getState() === \PrefixedByPoP\GuzzleHttp\Promise\PromiseInterface::FULFILLED;
    }
    /**
     * Returns true if a promise is rejected.
     *
     * @return bool
     */
    public static function rejected(\PrefixedByPoP\GuzzleHttp\Promise\PromiseInterface $promise)
    {
        return $promise->getState() === \PrefixedByPoP\GuzzleHttp\Promise\PromiseInterface::REJECTED;
    }
}
