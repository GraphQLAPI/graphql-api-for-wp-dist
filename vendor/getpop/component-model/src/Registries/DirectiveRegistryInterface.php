<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Registries;

use PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface;
interface DirectiveRegistryInterface
{
    /**
     * @param \PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface $directiveResolver
     */
    public function addFieldDirectiveResolver($directiveResolver) : void;
    /**
     * @return array<string,FieldDirectiveResolverInterface>
     */
    public function getFieldDirectiveResolvers() : array;
    /**
     * @param string $directiveName
     */
    public function getFieldDirectiveResolver($directiveName) : ?FieldDirectiveResolverInterface;
}
