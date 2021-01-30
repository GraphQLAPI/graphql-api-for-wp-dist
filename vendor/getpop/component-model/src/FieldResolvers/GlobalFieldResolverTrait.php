<?php

declare (strict_types=1);
namespace PoP\ComponentModel\FieldResolvers;

use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
trait GlobalFieldResolverTrait
{
    public static function getClassesToAttachTo() : array
    {
        return [\PoP\ComponentModel\TypeResolvers\AbstractTypeResolver::class];
    }
    public function isGlobal(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : bool
    {
        return \true;
    }
}
