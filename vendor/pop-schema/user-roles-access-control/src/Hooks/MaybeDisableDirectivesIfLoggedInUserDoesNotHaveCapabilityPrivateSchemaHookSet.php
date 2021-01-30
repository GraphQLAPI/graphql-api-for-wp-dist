<?php

declare (strict_types=1);
namespace PoPSchema\UserRolesAccessControl\Hooks;

use PoPSchema\UserRolesAccessControl\Helpers\UserRoleHelper;
use PoP\AccessControl\Facades\AccessControlManagerFacade;
use PoPSchema\UserRolesAccessControl\Services\AccessControlGroups;
use PoPSchema\UserRolesAccessControl\Hooks\AbstractMaybeDisableDirectivesIfLoggedInUserDoesNotHaveItemPrivateSchemaHookSet;
class MaybeDisableDirectivesIfLoggedInUserDoesNotHaveCapabilityPrivateSchemaHookSet extends \PoPSchema\UserRolesAccessControl\Hooks\AbstractMaybeDisableDirectivesIfLoggedInUserDoesNotHaveItemPrivateSchemaHookSet
{
    /**
     * Configuration entries
     *
     * @return array
     */
    protected function getConfigurationEntries() : array
    {
        $accessControlManager = \PoP\AccessControl\Facades\AccessControlManagerFacade::getInstance();
        return $accessControlManager->getEntriesForDirectives(\PoPSchema\UserRolesAccessControl\Services\AccessControlGroups::CAPABILITIES);
    }
    /**
     * Indicate if the user has the item, to be implemented
     *
     * @param string $item
     * @return boolean
     */
    protected function doesCurrentUserHaveAnyItem(array $capabilities) : bool
    {
        return \PoPSchema\UserRolesAccessControl\Helpers\UserRoleHelper::doesCurrentUserHaveAnyCapability($capabilities);
    }
}
