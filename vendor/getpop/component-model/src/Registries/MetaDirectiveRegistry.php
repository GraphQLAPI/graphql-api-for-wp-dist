<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Registries;

use PoP\ComponentModel\DirectiveResolvers\MetaFieldDirectiveResolverInterface;
class MetaDirectiveRegistry implements \PoP\ComponentModel\Registries\MetaDirectiveRegistryInterface
{
    /**
     * @var array<string,MetaFieldDirectiveResolverInterface>
     */
    protected $metaFieldDirectiveResolvers = [];
    /**
     * @param \PoP\ComponentModel\DirectiveResolvers\MetaFieldDirectiveResolverInterface $metaFieldDirectiveResolver
     */
    public function addMetaFieldDirectiveResolver($metaFieldDirectiveResolver) : void
    {
        $this->metaFieldDirectiveResolvers[$metaFieldDirectiveResolver->getDirectiveName()] = $metaFieldDirectiveResolver;
    }
    /**
     * @return array<string,MetaFieldDirectiveResolverInterface>
     */
    public function getMetaFieldDirectiveResolvers() : array
    {
        return $this->metaFieldDirectiveResolvers;
    }
    /**
     * @param string $directiveName
     */
    public function getMetaFieldDirectiveResolver($directiveName) : ?MetaFieldDirectiveResolverInterface
    {
        return $this->metaFieldDirectiveResolvers[$directiveName] ?? null;
    }
}
