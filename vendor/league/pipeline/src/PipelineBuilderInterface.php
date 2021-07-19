<?php

declare (strict_types=1);
namespace PrefixedByPoP\League\Pipeline;

interface PipelineBuilderInterface
{
    /**
     * Add an stage.
     *
     * @return self
     */
    public function add(callable $stage) : PipelineBuilderInterface;
    /**
     * Build a new Pipeline object.
     * @param \League\Pipeline\ProcessorInterface $processor
     */
    public function build($processor = null) : PipelineInterface;
}
