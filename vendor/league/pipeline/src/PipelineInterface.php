<?php

declare (strict_types=1);
namespace PrefixedByPoP\League\Pipeline;

interface PipelineInterface extends \PrefixedByPoP\League\Pipeline\StageInterface
{
    /**
     * Create a new pipeline with an appended stage.
     *
     * @return static
     */
    public function pipe(callable $operation) : \PrefixedByPoP\League\Pipeline\PipelineInterface;
}
