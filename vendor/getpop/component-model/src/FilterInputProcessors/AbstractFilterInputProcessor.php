<?php

declare (strict_types=1);
namespace PoP\ComponentModel\FilterInputProcessors;

use PoP\ComponentModel\Instances\InstanceManagerInterface;
abstract class AbstractFilterInputProcessor implements \PoP\ComponentModel\FilterInputProcessors\FilterInputProcessorInterface
{
    /**
     * @var \PoP\ComponentModel\Instances\InstanceManagerInterface
     */
    protected $instanceManager;
    public function __construct(InstanceManagerInterface $instanceManager)
    {
        $this->instanceManager = $instanceManager;
    }
    public function getFilterInputsToProcess() : array
    {
        return [];
    }
}
