<?php

declare (strict_types=1);
namespace PoPSchema\UserRolesAccessControl\DirectiveResolvers;

class ValidateDoesLoggedInUserHaveAnyCapabilityForDirectivesDirectiveResolver extends \PoPSchema\UserRolesAccessControl\DirectiveResolvers\ValidateDoesLoggedInUserHaveAnyCapabilityDirectiveResolver
{
    public function getDirectiveName() : string
    {
        return 'validateDoesLoggedInUserHaveAnyCapabilityForDirectives';
    }
    protected function isValidatingDirective() : bool
    {
        return \true;
    }
}
