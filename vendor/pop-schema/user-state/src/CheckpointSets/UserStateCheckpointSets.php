<?php

declare (strict_types=1);
namespace PoPSchema\UserState\CheckpointSets;

use PoP\Engine\CheckpointProcessors\RequestCheckpointProcessor;
use PoPSchema\UserState\CheckpointProcessors\UserStateCheckpointProcessor;
class UserStateCheckpointSets
{
    const NOTLOGGEDIN = array([\PoP\Engine\CheckpointProcessors\RequestCheckpointProcessor::class, \PoP\Engine\CheckpointProcessors\RequestCheckpointProcessor::DOING_POST], [\PoPSchema\UserState\CheckpointProcessors\UserStateCheckpointProcessor::class, \PoPSchema\UserState\CheckpointProcessors\UserStateCheckpointProcessor::USERNOTLOGGEDIN]);
    const LOGGEDIN_STATIC = array([\PoP\Engine\CheckpointProcessors\RequestCheckpointProcessor::class, \PoP\Engine\CheckpointProcessors\RequestCheckpointProcessor::DOING_POST], [\PoPSchema\UserState\CheckpointProcessors\UserStateCheckpointProcessor::class, \PoPSchema\UserState\CheckpointProcessors\UserStateCheckpointProcessor::USERLOGGEDIN]);
    const LOGGEDIN_DATAFROMSERVER = array([\PoPSchema\UserState\CheckpointProcessors\UserStateCheckpointProcessor::class, \PoPSchema\UserState\CheckpointProcessors\UserStateCheckpointProcessor::USERLOGGEDIN]);
}
