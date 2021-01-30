<?php

declare (strict_types=1);
namespace PrefixedByPoP\League\Pipeline;

class PipelineBuilder implements \PrefixedByPoP\League\Pipeline\PipelineBuilderInterface
{
    /**
     * @var callable[]
     */
    private $stages = [];
    /**
     * @return self
     */
    public function add(callable $stage) : \PrefixedByPoP\League\Pipeline\PipelineBuilderInterface
    {
        $this->stages[] = $stage;
        return $this;
    }
    public function build(\PrefixedByPoP\League\Pipeline\ProcessorInterface $processor = null) : \PrefixedByPoP\League\Pipeline\PipelineInterface
    {
        return new \PrefixedByPoP\League\Pipeline\Pipeline($processor, ...$this->stages);
    }
}
