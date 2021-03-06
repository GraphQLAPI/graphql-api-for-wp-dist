<?php

declare (strict_types=1);
namespace PoP\ComponentModel\CheckpointSets;

use PoP\ComponentModel\CheckpointProcessors\MutationCheckpointProcessor;
class CheckpointSets
{
    const CAN_EXECUTE_MUTATIONS = array([\PoP\ComponentModel\CheckpointProcessors\MutationCheckpointProcessor::class, \PoP\ComponentModel\CheckpointProcessors\MutationCheckpointProcessor::ENABLED_MUTATIONS]);
}
