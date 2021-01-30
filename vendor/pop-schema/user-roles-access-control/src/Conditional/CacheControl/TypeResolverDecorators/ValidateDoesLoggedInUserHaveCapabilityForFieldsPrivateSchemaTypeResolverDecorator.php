<?php

declare (strict_types=1);
namespace PoPSchema\UserRolesAccessControl\Conditional\CacheControl\TypeResolverDecorators;

use PoP\AccessControl\Facades\AccessControlManagerFacade;
use PoPSchema\UserRolesAccessControl\Services\AccessControlGroups;
use PoPSchema\UserRolesAccessControl\Conditional\CacheControl\TypeResolverDecorators\AbstractValidateDoesLoggedInUserHaveItemForFieldsPrivateSchemaTypeResolverDecorator;
class ValidateDoesLoggedInUserHaveCapabilityForFieldsPrivateSchemaTypeResolverDecorator extends \PoPSchema\UserRolesAccessControl\Conditional\CacheControl\TypeResolverDecorators\AbstractValidateDoesLoggedInUserHaveItemForFieldsPrivateSchemaTypeResolverDecorator
{
    protected static function getConfigurationEntries() : array
    {
        $accessControlManager = \PoP\AccessControl\Facades\AccessControlManagerFacade::getInstance();
        return $accessControlManager->getEntriesForFields(\PoPSchema\UserRolesAccessControl\Services\AccessControlGroups::CAPABILITIES);
    }
}
