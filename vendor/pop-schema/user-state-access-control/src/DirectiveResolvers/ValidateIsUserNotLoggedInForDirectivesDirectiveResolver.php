<?php

declare (strict_types=1);
namespace PoPSchema\UserStateAccessControl\DirectiveResolvers;

class ValidateIsUserNotLoggedInForDirectivesDirectiveResolver extends \PoPSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserNotLoggedInDirectiveResolver
{
    public function getDirectiveName() : string
    {
        return 'validateIsUserNotLoggedInForDirectives';
    }
    protected function isValidatingDirective() : bool
    {
        return \true;
    }
}
