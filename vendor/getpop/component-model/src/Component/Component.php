<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Component;

final class Component
{
    /**
     * @readonly
     * @var string
     */
    public $processorClass;
    /**
     * @readonly
     * @var string
     */
    public $name;
    /**
     * @var array<string, mixed>
     * @readonly
     */
    public $atts = [];
    /**
     * @param array<string,mixed> $atts
     */
    public function __construct(string $processorClass, string $name, array $atts = [])
    {
        $this->processorClass = $processorClass;
        $this->name = $name;
        $this->atts = $atts;
    }
    public function asString() : string
    {
        return \sprintf('[%s, %s%s]', $this->processorClass, $this->name, $this->atts !== [] ? ', ' . \json_encode($this->atts) : '');
    }
}
