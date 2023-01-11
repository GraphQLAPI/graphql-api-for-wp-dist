<?php

declare (strict_types=1);
namespace PrefixedByPoP\League\Pipeline;

interface ProcessorInterface
{
    /**
     * Process the payload using multiple stages.
     *
     * @param mixed $payload
     *
     * @return mixed
     * @param callable ...$stages
     */
    public function process($payload, ...$stages);
}
