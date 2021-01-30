<?php

declare (strict_types=1);
namespace PrefixedByPoP\League\Pipeline;

interface StageInterface
{
    /**
     * Process the payload.
     *
     * @param mixed $payload
     *
     * @return mixed
     */
    public function __invoke($payload);
}
