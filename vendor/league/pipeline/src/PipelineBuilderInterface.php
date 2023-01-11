<?php

declare (strict_types=1);
namespace PrefixedByPoP\League\Pipeline;

interface PipelineBuilderInterface
{
    /**
     * Add an stage.
     *
     * @return self
     * @param callable $stage
     */
    public function add($stage) : PipelineBuilderInterface;
    /**
     * Build a new Pipeline object.
     * @param \League\Pipeline\ProcessorInterface|null $processor
     */
    public function build($processor = null) : PipelineInterface;
}
