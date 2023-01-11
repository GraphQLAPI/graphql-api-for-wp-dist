<?php

declare (strict_types=1);
namespace PrefixedByPoP\League\Pipeline;

class InterruptibleProcessor implements ProcessorInterface
{
    /**
     * @var callable
     */
    private $check;
    public function __construct(callable $check)
    {
        $this->check = $check;
    }
    /**
     * @param callable ...$stages
     */
    public function process($payload, ...$stages)
    {
        $check = $this->check;
        foreach ($stages as $stage) {
            $payload = $stage($payload);
            if (\true !== $check($payload)) {
                return $payload;
            }
        }
        return $payload;
    }
}
