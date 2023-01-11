<?php

declare (strict_types=1);
namespace PrefixedByPoP\League\Pipeline;

class PipelineBuilder implements PipelineBuilderInterface
{
    /**
     * @var callable[]
     */
    private $stages = [];
    /**
     * @return self
     * @param callable $stage
     */
    public function add($stage) : PipelineBuilderInterface
    {
        $this->stages[] = $stage;
        return $this;
    }
    /**
     * @param \League\Pipeline\ProcessorInterface|null $processor
     */
    public function build($processor = null) : PipelineInterface
    {
        return new Pipeline($processor, ...$this->stages);
    }
}
