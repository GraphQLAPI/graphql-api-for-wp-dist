<?php

declare (strict_types=1);
namespace PoP\ComponentModel\DirectiveResolvers;

abstract class AbstractGlobalDirectiveResolver extends \PoP\ComponentModel\DirectiveResolvers\AbstractSchemaDirectiveResolver
{
    use GlobalDirectiveResolverTrait;
}
