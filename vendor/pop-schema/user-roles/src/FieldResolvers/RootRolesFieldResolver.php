<?php

declare (strict_types=1);
namespace PoPSchema\UserRoles\FieldResolvers;

use PoP\Engine\TypeResolvers\RootTypeResolver;
use PoPSchema\UserRoles\FieldResolvers\RolesFieldResolverTrait;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
class RootRolesFieldResolver extends \PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver
{
    use RolesFieldResolverTrait;
    public static function getClassesToAttachTo() : array
    {
        return [\PoP\Engine\TypeResolvers\RootTypeResolver::class];
    }
}
