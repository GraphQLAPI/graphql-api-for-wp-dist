<?php

declare (strict_types=1);
namespace PoP\ComponentModel\CheckpointProcessors;

abstract class AbstractCheckpointProcessor
{
    public abstract function getCheckpointsToProcess();
    public function process(array $checkpoint)
    {
        // By default, no problem at all, so always return true
        return \true;
    }
}
