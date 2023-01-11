<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Registries;

use PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface;
interface MandatoryFieldDirectiveResolverRegistryInterface
{
    /**
     * @param \PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface $directiveResolver
     */
    public function addMandatoryFieldDirectiveResolver($directiveResolver) : void;
    /**
     * @return FieldDirectiveResolverInterface[]
     */
    public function getMandatoryFieldDirectiveResolvers() : array;
}
