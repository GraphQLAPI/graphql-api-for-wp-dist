<?php

declare (strict_types=1);
namespace PrefixedByPoP\League\Pipeline;

class Pipeline implements \PrefixedByPoP\League\Pipeline\PipelineInterface
{
    /**
     * @var callable[]
     */
    private $stages = [];
    /**
     * @var ProcessorInterface
     */
    private $processor;
    public function __construct(\PrefixedByPoP\League\Pipeline\ProcessorInterface $processor = null, callable ...$stages)
    {
        $this->processor = $processor ?? new \PrefixedByPoP\League\Pipeline\FingersCrossedProcessor();
        $this->stages = $stages;
    }
    public function pipe(callable $stage) : \PrefixedByPoP\League\Pipeline\PipelineInterface
    {
        $pipeline = clone $this;
        $pipeline->stages[] = $stage;
        return $pipeline;
    }
    public function process($payload)
    {
        return $this->processor->process($payload, ...$this->stages);
    }
    public function __invoke($payload)
    {
        return $this->process($payload);
    }
}
