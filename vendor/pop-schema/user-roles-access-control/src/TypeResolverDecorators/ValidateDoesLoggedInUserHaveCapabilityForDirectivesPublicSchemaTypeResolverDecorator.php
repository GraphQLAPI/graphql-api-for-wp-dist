<?php

declare (strict_types=1);
namespace PoPSchema\UserRolesAccessControl\TypeResolverDecorators;

use PoP\AccessControl\Facades\AccessControlManagerFacade;
use PoPSchema\UserRolesAccessControl\Services\AccessControlGroups;
use PoPSchema\UserRolesAccessControl\DirectiveResolvers\ValidateDoesLoggedInUserHaveAnyCapabilityForDirectivesDirectiveResolver;
use PoP\AccessControl\TypeResolverDecorators\AbstractConfigurableAccessControlForDirectivesInPublicSchemaTypeResolverDecorator;
class ValidateDoesLoggedInUserHaveCapabilityForDirectivesPublicSchemaTypeResolverDecorator extends \PoP\AccessControl\TypeResolverDecorators\AbstractConfigurableAccessControlForDirectivesInPublicSchemaTypeResolverDecorator
{
    use ValidateDoesLoggedInUserHaveCapabilityPublicSchemaTypeResolverDecoratorTrait;
    protected function getConfigurationEntries() : array
    {
        $accessControlManager = \PoP\AccessControl\Facades\AccessControlManagerFacade::getInstance();
        return $accessControlManager->getEntriesForDirectives(\PoPSchema\UserRolesAccessControl\Services\AccessControlGroups::CAPABILITIES);
    }
    protected function getValidateCapabilityDirectiveResolverClass() : string
    {
        return \PoPSchema\UserRolesAccessControl\DirectiveResolvers\ValidateDoesLoggedInUserHaveAnyCapabilityForDirectivesDirectiveResolver::class;
    }
}
