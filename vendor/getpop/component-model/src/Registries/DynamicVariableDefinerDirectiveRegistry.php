<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Registries;

use PoP\ComponentModel\DirectiveResolvers\DynamicVariableDefinerFieldDirectiveResolverInterface;
class DynamicVariableDefinerDirectiveRegistry implements \PoP\ComponentModel\Registries\DynamicVariableDefinerDirectiveRegistryInterface
{
    /**
     * @var array<string,DynamicVariableDefinerFieldDirectiveResolverInterface>
     */
    protected $dynamicVariableDefinerFieldDirectiveResolvers = [];
    /**
     * @param \PoP\ComponentModel\DirectiveResolvers\DynamicVariableDefinerFieldDirectiveResolverInterface $dynamicVariableDefinerFieldDirectiveResolver
     */
    public function addDynamicVariableDefinerFieldDirectiveResolver($dynamicVariableDefinerFieldDirectiveResolver) : void
    {
        $this->dynamicVariableDefinerFieldDirectiveResolvers[$dynamicVariableDefinerFieldDirectiveResolver->getDirectiveName()] = $dynamicVariableDefinerFieldDirectiveResolver;
    }
    /**
     * @return array<string,DynamicVariableDefinerFieldDirectiveResolverInterface>
     */
    public function getDynamicVariableDefinerFieldDirectiveResolvers() : array
    {
        return $this->dynamicVariableDefinerFieldDirectiveResolvers;
    }
    /**
     * @param string $directiveName
     */
    public function getDynamicVariableDefinerFieldDirectiveResolver($directiveName) : ?DynamicVariableDefinerFieldDirectiveResolverInterface
    {
        return $this->dynamicVariableDefinerFieldDirectiveResolvers[$directiveName] ?? null;
    }
}
