<?php

declare (strict_types=1);
namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
trait GlobalDirectiveResolverTrait
{
    public static function getClassesToAttachTo() : array
    {
        // Be attached to all typeResolvers
        return [\PoP\ComponentModel\TypeResolvers\AbstractTypeResolver::class];
    }
    public function isGlobal(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver) : bool
    {
        return \true;
    }
}
