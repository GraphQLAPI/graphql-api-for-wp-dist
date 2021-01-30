<?php

declare (strict_types=1);
namespace PoPSchema\UserState\FieldResolvers;

use PoPSchema\UserState\FieldResolvers\UserStateFieldResolverTrait;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
abstract class AbstractUserStateFieldResolver extends \PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver
{
    use UserStateFieldResolverTrait;
}
