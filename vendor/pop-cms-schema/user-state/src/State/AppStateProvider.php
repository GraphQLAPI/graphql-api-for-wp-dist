<?php

declare (strict_types=1);
namespace PoPCMSSchema\UserState\State;

use PoP\Root\State\AbstractAppStateProvider;
use PoPCMSSchema\UserState\TypeAPIs\UserStateTypeAPIInterface;
class AppStateProvider extends AbstractAppStateProvider
{
    /**
     * @var \PoPCMSSchema\UserState\TypeAPIs\UserStateTypeAPIInterface|null
     */
    private $userStateTypeAPI;
    /**
     * @param \PoPCMSSchema\UserState\TypeAPIs\UserStateTypeAPIInterface $userStateTypeAPI
     */
    public final function setUserStateTypeAPI($userStateTypeAPI) : void
    {
        $this->userStateTypeAPI = $userStateTypeAPI;
    }
    protected final function getUserStateTypeAPI() : UserStateTypeAPIInterface
    {
        /** @var UserStateTypeAPIInterface */
        return $this->userStateTypeAPI = $this->userStateTypeAPI ?? $this->instanceManager->getInstance(UserStateTypeAPIInterface::class);
    }
    /**
     * @param array<string,mixed> $state
     */
    public function initialize(&$state) : void
    {
        $this->setUserStateVars($state);
    }
    /**
     * Add the user's (non)logged-in state
     *
     * @param array<string,mixed> $state
     */
    public function setUserStateVars(&$state) : void
    {
        if ($this->getUserStateTypeAPI()->isUserLoggedIn()) {
            $state['is-user-logged-in'] = \true;
            $state['current-user'] = $this->getUserStateTypeAPI()->getCurrentUser();
            $state['current-user-id'] = $this->getUserStateTypeAPI()->getCurrentUserID();
            return;
        }
        $state['is-user-logged-in'] = \false;
        $state['current-user'] = null;
        $state['current-user-id'] = null;
    }
}
