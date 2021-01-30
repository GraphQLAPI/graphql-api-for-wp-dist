<?php

declare (strict_types=1);
namespace PoPSchema\UserStateAccessControl\Hooks;

use PoP\ComponentModel\State\ApplicationState;
use PoP\AccessControl\Facades\AccessControlManagerFacade;
use PoPSchema\UserStateAccessControl\Services\AccessControlGroups;
use PoP\AccessControl\Hooks\AbstractConfigurableAccessControlForFieldsInPrivateSchemaHookSet;
abstract class AbstractUserStateConfigurableAccessControlForFieldsInPrivateSchemaHookSet extends \PoP\AccessControl\Hooks\AbstractConfigurableAccessControlForFieldsInPrivateSchemaHookSet
{
    /**
     * Configuration entries
     *
     * @return array
     */
    protected static function getConfigurationEntries() : array
    {
        $accessControlManager = \PoP\AccessControl\Facades\AccessControlManagerFacade::getInstance();
        return $accessControlManager->getEntriesForFields(\PoPSchema\UserStateAccessControl\Services\AccessControlGroups::STATE);
    }
    protected function removeFieldNameBasedOnMatchingEntryValue($entryValue = null) : bool
    {
        // Obtain the user state: logged in or not
        $vars = \PoP\ComponentModel\State\ApplicationState::getVars();
        $isUserLoggedIn = $vars['global-userstate']['is-user-logged-in'];
        // Let the implementation class decide if to remove the field or not
        return $this->removeFieldNameBasedOnUserState((string) $entryValue, $isUserLoggedIn);
    }
    protected abstract function removeFieldNameBasedOnUserState(string $entryValue, bool $isUserLoggedIn) : bool;
}
