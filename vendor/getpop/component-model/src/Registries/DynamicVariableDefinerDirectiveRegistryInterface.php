<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Registries;

use PoP\ComponentModel\DirectiveResolvers\DynamicVariableDefinerFieldDirectiveResolverInterface;
interface DynamicVariableDefinerDirectiveRegistryInterface
{
    /**
     * @param \PoP\ComponentModel\DirectiveResolvers\DynamicVariableDefinerFieldDirectiveResolverInterface $metaFieldDirectiveResolver
     */
    public function addDynamicVariableDefinerFieldDirectiveResolver($metaFieldDirectiveResolver) : void;
    /**
     * @return array<string,DynamicVariableDefinerFieldDirectiveResolverInterface>
     */
    public function getDynamicVariableDefinerFieldDirectiveResolvers() : array;
    /**
     * @param string $directiveName
     */
    public function getDynamicVariableDefinerFieldDirectiveResolver($directiveName) : ?DynamicVariableDefinerFieldDirectiveResolverInterface;
}
