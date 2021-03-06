<?php

declare (strict_types=1);
namespace PoPSchema\UserRolesAccessControl\TypeResolverDecorators;

use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
use PoPSchema\UserStateAccessControl\TypeResolverDecorators\AbstractValidateIsUserLoggedInForDirectivesPublicSchemaTypeResolverDecorator;
use PoPSchema\UserRolesAccessControl\DirectiveResolvers\ValidateDoesLoggedInUserHaveAnyRoleForDirectivesDirectiveResolver;
use PoPSchema\UserRolesAccessControl\DirectiveResolvers\ValidateDoesLoggedInUserHaveAnyCapabilityForDirectivesDirectiveResolver;
class GlobalValidateIsUserLoggedInForDirectivesPublicSchemaTypeResolverDecorator extends \PoPSchema\UserStateAccessControl\TypeResolverDecorators\AbstractValidateIsUserLoggedInForDirectivesPublicSchemaTypeResolverDecorator
{
    public static function getClassesToAttachTo() : array
    {
        return array(\PoP\ComponentModel\TypeResolvers\AbstractTypeResolver::class);
    }
    /**
     * Provide the classes for all the directiveResolverClasses that need the "validateIsUserLoggedIn" directive
     *
     * @return array
     */
    protected function getDirectiveResolverClasses() : array
    {
        return [\PoPSchema\UserRolesAccessControl\DirectiveResolvers\ValidateDoesLoggedInUserHaveAnyRoleForDirectivesDirectiveResolver::class, \PoPSchema\UserRolesAccessControl\DirectiveResolvers\ValidateDoesLoggedInUserHaveAnyCapabilityForDirectivesDirectiveResolver::class];
    }
}
