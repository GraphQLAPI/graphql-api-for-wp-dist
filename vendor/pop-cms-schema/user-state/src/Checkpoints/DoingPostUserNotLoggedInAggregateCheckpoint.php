<?php

declare (strict_types=1);
namespace PoPCMSSchema\UserState\Checkpoints;

use PoP\ComponentModel\Checkpoints\AbstractAggregateCheckpoint;
use PoP\ComponentModel\Checkpoints\CheckpointInterface;
use PoP\Engine\Checkpoints\DoingPostCheckpoint;
use PoPCMSSchema\UserState\Checkpoints\UserNotLoggedInCheckpoint;
class DoingPostUserNotLoggedInAggregateCheckpoint extends AbstractAggregateCheckpoint
{
    /**
     * @var \PoPCMSSchema\UserState\Checkpoints\UserNotLoggedInCheckpoint|null
     */
    private $userNotLoggedInCheckpoint;
    /**
     * @var \PoP\Engine\Checkpoints\DoingPostCheckpoint|null
     */
    private $doingPostCheckpoint;
    /**
     * @param \PoPCMSSchema\UserState\Checkpoints\UserNotLoggedInCheckpoint $userNotLoggedInCheckpoint
     */
    public final function setUserNotLoggedInCheckpoint($userNotLoggedInCheckpoint) : void
    {
        $this->userNotLoggedInCheckpoint = $userNotLoggedInCheckpoint;
    }
    protected final function getUserNotLoggedInCheckpoint() : UserNotLoggedInCheckpoint
    {
        /** @var UserNotLoggedInCheckpoint */
        return $this->userNotLoggedInCheckpoint = $this->userNotLoggedInCheckpoint ?? $this->instanceManager->getInstance(UserNotLoggedInCheckpoint::class);
    }
    /**
     * @param \PoP\Engine\Checkpoints\DoingPostCheckpoint $doingPostCheckpoint
     */
    public final function setDoingPostCheckpoint($doingPostCheckpoint) : void
    {
        $this->doingPostCheckpoint = $doingPostCheckpoint;
    }
    protected final function getDoingPostCheckpoint() : DoingPostCheckpoint
    {
        /** @var DoingPostCheckpoint */
        return $this->doingPostCheckpoint = $this->doingPostCheckpoint ?? $this->instanceManager->getInstance(DoingPostCheckpoint::class);
    }
    /**
     * @return CheckpointInterface[]
     */
    protected function getCheckpoints() : array
    {
        return [$this->getDoingPostCheckpoint(), $this->getUserNotLoggedInCheckpoint()];
    }
}
