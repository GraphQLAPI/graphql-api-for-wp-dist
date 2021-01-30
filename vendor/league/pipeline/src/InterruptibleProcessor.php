<?php

declare (strict_types=1);
namespace PrefixedByPoP\League\Pipeline;

class InterruptibleProcessor implements \PrefixedByPoP\League\Pipeline\ProcessorInterface
{
    /**
     * @var callable
     */
    private $check;
    public function __construct(callable $check)
    {
        $this->check = $check;
    }
    public function process($payload, callable ...$stages)
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
