<?php

declare (strict_types=1);
namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\ComponentModel\TypeResolvers\AbstractRelationalTypeResolver;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
trait GlobalFieldDirectiveResolverTrait
{
    /**
     * @param \PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface $relationalTypeResolver
     */
    public function isGlobal($relationalTypeResolver) : bool
    {
        return \true;
    }
    /**
     * @return array<class-string<InterfaceTypeResolverInterface|RelationalTypeResolverInterface>>
     */
    public function getRelationalTypeOrInterfaceTypeResolverClassesToAttachTo() : array
    {
        // Global: Be attached to all RelationalTypeResolvers
        return [AbstractRelationalTypeResolver::class];
    }
}
