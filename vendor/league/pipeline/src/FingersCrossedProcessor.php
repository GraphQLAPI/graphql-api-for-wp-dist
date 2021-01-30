<?php

declare (strict_types=1);
namespace PrefixedByPoP\League\Pipeline;

class FingersCrossedProcessor implements \PrefixedByPoP\League\Pipeline\ProcessorInterface
{
    public function process($payload, callable ...$stages)
    {
        foreach ($stages as $stage) {
            $payload = $stage($payload);
        }
        return $payload;
    }
}
