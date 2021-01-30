<?php

declare (strict_types=1);
namespace PoPSchema\UserRolesAccessControl\Conditional\CacheControl\TypeResolverDecorators;

use PoP\AccessControl\Facades\AccessControlManagerFacade;
use PoPSchema\UserRolesAccessControl\Services\AccessControlGroups;
use PoPSchema\UserRolesAccessControl\Conditional\CacheControl\TypeResolverDecorators\AbstractValidateDoesLoggedInUserHaveItemForDirectivesPrivateSchemaTypeResolverDecorator;
class ValidateDoesLoggedInUserHaveCapabilityForDirectivesPrivateSchemaTypeResolverDecorator extends \PoPSchema\UserRolesAccessControl\Conditional\CacheControl\TypeResolverDecorators\AbstractValidateDoesLoggedInUserHaveItemForDirectivesPrivateSchemaTypeResolverDecorator
{
    protected function getConfigurationEntries() : array
    {
        $accessControlManager = \PoP\AccessControl\Facades\AccessControlManagerFacade::getInstance();
        return $accessControlManager->getEntriesForDirectives(\PoPSchema\UserRolesAccessControl\Services\AccessControlGroups::CAPABILITIES);
    }
}
