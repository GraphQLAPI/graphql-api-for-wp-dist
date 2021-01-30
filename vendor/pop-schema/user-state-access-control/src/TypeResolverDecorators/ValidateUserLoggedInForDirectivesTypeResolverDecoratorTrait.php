<?php

declare (strict_types=1);
namespace PoPSchema\UserStateAccessControl\TypeResolverDecorators;

use PoPSchema\UserStateAccessControl\ConfigurationEntries\UserStates;
use PoPSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserLoggedInForDirectivesDirectiveResolver;
trait ValidateUserLoggedInForDirectivesTypeResolverDecoratorTrait
{
    protected function getRequiredEntryValue() : ?string
    {
        return \PoPSchema\UserStateAccessControl\ConfigurationEntries\UserStates::IN;
    }
    protected function getValidateUserStateDirectiveResolverClass() : string
    {
        return \PoPSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserLoggedInForDirectivesDirectiveResolver::class;
    }
}
