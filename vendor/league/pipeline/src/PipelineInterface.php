<?php

declare (strict_types=1);
namespace PrefixedByPoP\League\Pipeline;

interface PipelineInterface extends StageInterface
{
    /**
     * Create a new pipeline with an appended stage.
     *
     * @return static
     * @param callable $operation
     */
    public function pipe($operation) : PipelineInterface;
}
