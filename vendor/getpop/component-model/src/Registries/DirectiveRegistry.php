<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Registries;

use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
class DirectiveRegistry implements \PoP\ComponentModel\Registries\DirectiveRegistryInterface
{
    /**
     * @var DirectiveResolverInterface[]
     */
    protected $directiveResolvers = [];
    public function addDirectiveResolver(DirectiveResolverInterface $directiveResolver) : void
    {
        $this->directiveResolvers[] = $directiveResolver;
    }
    /**
     * @return DirectiveResolverInterface[]
     */
    public function getDirectiveResolvers() : array
    {
        return $this->directiveResolvers;
    }
}
