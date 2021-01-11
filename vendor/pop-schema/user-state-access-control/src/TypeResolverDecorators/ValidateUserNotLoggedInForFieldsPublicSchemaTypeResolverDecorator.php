<?php

declare(strict_types=1);

namespace PoPSchema\UserStateAccessControl\TypeResolverDecorators;

use PoPSchema\UserStateAccessControl\TypeResolverDecorators\ValidateUserNotLoggedInForFieldsTypeResolverDecoratorTrait;

class ValidateUserNotLoggedInForFieldsPublicSchemaTypeResolverDecorator extends AbstractUserStateConfigurableAccessControlForFieldsInPublicSchemaTypeResolverDecorator
{
    use ValidateUserNotLoggedInForFieldsTypeResolverDecoratorTrait;
}