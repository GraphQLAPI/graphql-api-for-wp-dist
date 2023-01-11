<?php

declare (strict_types=1);
namespace PrefixedByPoP\League\Pipeline;

class FingersCrossedProcessor implements ProcessorInterface
{
    /**
     * @param callable ...$stages
     */
    public function process($payload, ...$stages)
    {
        foreach ($stages as $stage) {
            $payload = $stage($payload);
        }
        return $payload;
    }
}
