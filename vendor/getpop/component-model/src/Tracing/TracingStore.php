<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Tracing;

class TracingStore
{
    /** @var TraceInterface[] */
    private $traces = [];
    /**
     * @return TraceInterface[]
     */
    public function getTraces() : array
    {
        return $this->traces;
    }
    /**
     * @param \PoP\ComponentModel\Tracing\TraceInterface $trace
     */
    public function addTrace($trace) : void
    {
        $this->traces[] = $trace;
    }
    /**
     * @param TraceInterface[] $traces
     */
    public function setTraces($traces) : void
    {
        $this->traces = $traces;
    }
}
