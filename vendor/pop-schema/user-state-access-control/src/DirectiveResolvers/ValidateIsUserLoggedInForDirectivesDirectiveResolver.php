<?php

declare (strict_types=1);
namespace PoPSchema\UserStateAccessControl\DirectiveResolvers;

class ValidateIsUserLoggedInForDirectivesDirectiveResolver extends \PoPSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserLoggedInDirectiveResolver
{
    const DIRECTIVE_NAME = 'validateIsUserLoggedInForDirectives';
    public static function getDirectiveName() : string
    {
        return self::DIRECTIVE_NAME;
    }
    protected function isValidatingDirective() : bool
    {
        return \true;
    }
}
