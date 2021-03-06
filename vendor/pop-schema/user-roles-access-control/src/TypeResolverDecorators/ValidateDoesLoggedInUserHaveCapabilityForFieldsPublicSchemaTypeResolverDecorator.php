<?php

declare (strict_types=1);
namespace PoPSchema\UserRolesAccessControl\TypeResolverDecorators;

use PoP\AccessControl\Facades\AccessControlManagerFacade;
use PoPSchema\UserRolesAccessControl\Services\AccessControlGroups;
use PoP\AccessControl\TypeResolverDecorators\AbstractPublicSchemaTypeResolverDecorator;
use PoP\AccessControl\TypeResolverDecorators\ConfigurableAccessControlForFieldsTypeResolverDecoratorTrait;
use PoPSchema\UserRolesAccessControl\DirectiveResolvers\ValidateDoesLoggedInUserHaveAnyCapabilityDirectiveResolver;
class ValidateDoesLoggedInUserHaveCapabilityForFieldsPublicSchemaTypeResolverDecorator extends \PoP\AccessControl\TypeResolverDecorators\AbstractPublicSchemaTypeResolverDecorator
{
    use ConfigurableAccessControlForFieldsTypeResolverDecoratorTrait;
    use ValidateDoesLoggedInUserHaveCapabilityPublicSchemaTypeResolverDecoratorTrait;
    protected static function getConfigurationEntries() : array
    {
        $accessControlManager = \PoP\AccessControl\Facades\AccessControlManagerFacade::getInstance();
        return $accessControlManager->getEntriesForFields(\PoPSchema\UserRolesAccessControl\Services\AccessControlGroups::CAPABILITIES);
    }
    protected function getValidateCapabilityDirectiveResolverClass() : string
    {
        return \PoPSchema\UserRolesAccessControl\DirectiveResolvers\ValidateDoesLoggedInUserHaveAnyCapabilityDirectiveResolver::class;
    }
}
