<?php

declare (strict_types=1);
namespace PoPSchema\UserRolesAccessControl\TypeResolverDecorators;

use PoP\AccessControl\Facades\AccessControlManagerFacade;
use PoPSchema\UserRolesAccessControl\Services\AccessControlGroups;
use PoP\AccessControl\TypeResolverDecorators\AbstractPublicSchemaTypeResolverDecorator;
use PoPSchema\UserRolesAccessControl\DirectiveResolvers\ValidateDoesLoggedInUserHaveAnyRoleDirectiveResolver;
use PoP\AccessControl\TypeResolverDecorators\ConfigurableAccessControlForFieldsTypeResolverDecoratorTrait;
class ValidateDoesLoggedInUserHaveRoleForFieldsPublicSchemaTypeResolverDecorator extends \PoP\AccessControl\TypeResolverDecorators\AbstractPublicSchemaTypeResolverDecorator
{
    use ConfigurableAccessControlForFieldsTypeResolverDecoratorTrait;
    use ValidateDoesLoggedInUserHaveRolePublicSchemaTypeResolverDecoratorTrait;
    protected static function getConfigurationEntries() : array
    {
        $accessControlManager = \PoP\AccessControl\Facades\AccessControlManagerFacade::getInstance();
        return $accessControlManager->getEntriesForFields(\PoPSchema\UserRolesAccessControl\Services\AccessControlGroups::ROLES);
    }
    protected function getValidateRoleDirectiveResolverClass() : string
    {
        return \PoPSchema\UserRolesAccessControl\DirectiveResolvers\ValidateDoesLoggedInUserHaveAnyRoleDirectiveResolver::class;
    }
}
