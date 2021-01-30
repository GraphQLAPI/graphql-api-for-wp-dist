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
    public function add(callable $stage) : \PrefixedByPoP\League\Pipeline\PipelineBuilderInterface;
    /**
     * Build a new Pipeline object.
     */
    public function build(\PrefixedByPoP\League\Pipeline\ProcessorInterface $processor = null) : \PrefixedByPoP\League\Pipeline\PipelineInterface;
}
