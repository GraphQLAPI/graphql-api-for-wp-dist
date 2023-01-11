<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Checkpoints;

use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\Root\Services\BasicServiceTrait;
abstract class AbstractCheckpoint implements \PoP\ComponentModel\Checkpoints\CheckpointInterface
{
    use BasicServiceTrait;
    /**
     * By default there's no problem
     */
    public function validateCheckpoint() : ?FeedbackItemResolution
    {
        return null;
    }
}
