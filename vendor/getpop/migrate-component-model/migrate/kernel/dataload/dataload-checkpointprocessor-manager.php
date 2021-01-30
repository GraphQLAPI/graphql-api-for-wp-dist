<?php

namespace PoP\ComponentModel;

use PoP\ComponentModel\ItemProcessors\ItemProcessorManagerTrait;
class CheckpointProcessorManager
{
    use ItemProcessorManagerTrait;
    public function __construct()
    {
        \PoP\ComponentModel\CheckpointProcessorManagerFactory::setInstance($this);
    }
}
/**
 * Initialization
 */
new \PoP\ComponentModel\CheckpointProcessorManager();
