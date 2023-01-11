<?php

declare (strict_types=1);
namespace PoPCMSSchema\UserState\Checkpoints;

use PoP\ComponentModel\Checkpoints\AbstractAggregateCheckpoint;
use PoP\ComponentModel\Checkpoints\CheckpointInterface;
use PoP\Engine\Checkpoints\DoingPostCheckpoint;
use PoPCMSSchema\UserState\Checkpoints\UserLoggedInCheckpoint;
class DoingPostUserLoggedInAggregateCheckpoint extends AbstractAggregateCheckpoint
{
    /**
     * @var \PoPCMSSchema\UserState\Checkpoints\UserLoggedInCheckpoint|null
     */
    private $userLoggedInCheckpoint;
    /**
     * @var \PoP\Engine\Checkpoints\DoingPostCheckpoint|null
     */
    private $doingPostCheckpoint;
    /**
     * @param \PoPCMSSchema\UserState\Checkpoints\UserLoggedInCheckpoint $userLoggedInCheckpoint
     */
    public final function setUserLoggedInCheckpoint($userLoggedInCheckpoint) : void
    {
        $this->userLoggedInCheckpoint = $userLoggedInCheckpoint;
    }
    protected final function getUserLoggedInCheckpoint() : UserLoggedInCheckpoint
    {
        /** @var UserLoggedInCheckpoint */
        return $this->userLoggedInCheckpoint = $this->userLoggedInCheckpoint ?? $this->instanceManager->getInstance(UserLoggedInCheckpoint::class);
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
        return [$this->getDoingPostCheckpoint(), $this->getUserLoggedInCheckpoint()];
    }
}
