<?php

declare (strict_types=1);
namespace PoP\Engine\DirectiveResolvers;

use PoP\ComponentModel\DirectiveResolvers\GlobalFieldDirectiveResolverTrait;
abstract class AbstractGlobalFieldDirectiveResolver extends \PoP\Engine\DirectiveResolvers\AbstractFieldDirectiveResolver
{
    use GlobalFieldDirectiveResolverTrait;
}
