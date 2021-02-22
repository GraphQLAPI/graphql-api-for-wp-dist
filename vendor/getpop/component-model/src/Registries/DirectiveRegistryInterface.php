<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Registries;

use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
interface DirectiveRegistryInterface
{
    public function addDirectiveResolver(\PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface $directiveResolver) : void;
    /**
     * @return DirectiveResolverInterface[]
     */
    public function getDirectiveResolvers() : array;
}
