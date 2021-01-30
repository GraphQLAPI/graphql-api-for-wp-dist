<?php

declare (strict_types=1);
namespace PoPSchema\UserStateAccessControl\Hooks;

use PoP\ComponentModel\State\ApplicationState;
use PoP\AccessControl\Facades\AccessControlManagerFacade;
use PoPSchema\UserStateAccessControl\Services\AccessControlGroups;
use PoP\AccessControl\Hooks\AbstractConfigurableAccessControlForDirectivesInPrivateSchemaHookSet;
abstract class AbstractUserStateConfigurableAccessControlForDirectivesInPrivateSchemaHookSet extends \PoP\AccessControl\Hooks\AbstractConfigurableAccessControlForDirectivesInPrivateSchemaHookSet
{
    protected function enabled() : bool
    {
        $vars = \PoP\ComponentModel\State\ApplicationState::getVars();
        $isUserLoggedIn = $vars['global-userstate']['is-user-logged-in'];
        return parent::enabled() && $this->enableBasedOnUserState($isUserLoggedIn);
    }
    protected abstract function enableBasedOnUserState(bool $isUserLoggedIn) : bool;
    /**
     * Configuration entries
     *
     * @return array
     */
    protected function getConfigurationEntries() : array
    {
        $accessControlManager = \PoP\AccessControl\Facades\AccessControlManagerFacade::getInstance();
        return $accessControlManager->getEntriesForDirectives(\PoPSchema\UserStateAccessControl\Services\AccessControlGroups::STATE);
    }
}
