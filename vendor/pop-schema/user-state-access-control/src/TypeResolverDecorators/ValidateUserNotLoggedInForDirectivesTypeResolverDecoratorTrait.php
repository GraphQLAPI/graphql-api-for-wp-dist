<?php

declare (strict_types=1);
namespace PoPSchema\UserStateAccessControl\TypeResolverDecorators;

use PoPSchema\UserStateAccessControl\ConfigurationEntries\UserStates;
use PoPSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserNotLoggedInForDirectivesDirectiveResolver;
trait ValidateUserNotLoggedInForDirectivesTypeResolverDecoratorTrait
{
    protected function getRequiredEntryValue() : ?string
    {
        return \PoPSchema\UserStateAccessControl\ConfigurationEntries\UserStates::OUT;
    }
    protected function getValidateUserStateDirectiveResolverClass() : string
    {
        return \PoPSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserNotLoggedInForDirectivesDirectiveResolver::class;
    }
}
