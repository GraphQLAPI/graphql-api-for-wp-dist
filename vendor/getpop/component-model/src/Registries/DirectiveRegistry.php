<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Registries;

use PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface;
class DirectiveRegistry implements \PoP\ComponentModel\Registries\DirectiveRegistryInterface
{
    /**
     * @var array<string,FieldDirectiveResolverInterface>
     */
    protected $directiveResolvers = [];
    /**
     * @param \PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface $directiveResolver
     */
    public function addFieldDirectiveResolver($directiveResolver) : void
    {
        $this->directiveResolvers[$directiveResolver->getDirectiveName()] = $directiveResolver;
    }
    /**
     * @return array<string,FieldDirectiveResolverInterface>
     */
    public function getFieldDirectiveResolvers() : array
    {
        return $this->directiveResolvers;
    }
    /**
     * @param string $directiveName
     */
    public function getFieldDirectiveResolver($directiveName) : ?FieldDirectiveResolverInterface
    {
        return $this->directiveResolvers[$directiveName] ?? null;
    }
}
