<?php

declare (strict_types=1);
namespace PoPSchema\UserRolesAccessControl\DirectiveResolvers;

class ValidateDoesLoggedInUserHaveAnyRoleForDirectivesDirectiveResolver extends \PoPSchema\UserRolesAccessControl\DirectiveResolvers\ValidateDoesLoggedInUserHaveAnyRoleDirectiveResolver
{
    const DIRECTIVE_NAME = 'validateDoesLoggedInUserHaveAnyRoleForDirectives';
    public static function getDirectiveName() : string
    {
        return self::DIRECTIVE_NAME;
    }
    protected function isValidatingDirective() : bool
    {
        return \true;
    }
}
