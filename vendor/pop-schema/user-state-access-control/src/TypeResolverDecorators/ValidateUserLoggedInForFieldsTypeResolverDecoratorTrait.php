<?php

declare (strict_types=1);
namespace PoPSchema\UserStateAccessControl\TypeResolverDecorators;

use PoPSchema\UserStateAccessControl\ConfigurationEntries\UserStates;
use PoPSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserLoggedInDirectiveResolver;
trait ValidateUserLoggedInForFieldsTypeResolverDecoratorTrait
{
    protected function removeFieldNameBasedOnMatchingEntryValue($entryValue = null) : bool
    {
        return \PoPSchema\UserStateAccessControl\ConfigurationEntries\UserStates::IN == $entryValue;
    }
    protected function getValidateUserStateDirectiveResolverClass() : string
    {
        return \PoPSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserLoggedInDirectiveResolver::class;
    }
}
