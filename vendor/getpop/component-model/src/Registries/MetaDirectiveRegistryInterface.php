<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Registries;

use PoP\ComponentModel\DirectiveResolvers\MetaFieldDirectiveResolverInterface;
interface MetaDirectiveRegistryInterface
{
    /**
     * @param \PoP\ComponentModel\DirectiveResolvers\MetaFieldDirectiveResolverInterface $metaFieldDirectiveResolver
     */
    public function addMetaFieldDirectiveResolver($metaFieldDirectiveResolver) : void;
    /**
     * @return array<string,MetaFieldDirectiveResolverInterface>
     */
    public function getMetaFieldDirectiveResolvers() : array;
    /**
     * @param string $directiveName
     */
    public function getMetaFieldDirectiveResolver($directiveName) : ?MetaFieldDirectiveResolverInterface;
}
