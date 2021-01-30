<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\TypeResolvers;

use PoP\Engine\TypeResolvers\ReservedNameTypeResolverTrait;
use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
abstract class AbstractIntrospectionTypeResolver extends \PoP\ComponentModel\TypeResolvers\AbstractTypeResolver
{
    use ReservedNameTypeResolverTrait;
}
