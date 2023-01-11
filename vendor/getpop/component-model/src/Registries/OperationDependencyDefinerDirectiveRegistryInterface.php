<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Registries;

use PoP\ComponentModel\DirectiveResolvers\OperationDependencyDefinerFieldDirectiveResolverInterface;
interface OperationDependencyDefinerDirectiveRegistryInterface
{
    /**
     * @param \PoP\ComponentModel\DirectiveResolvers\OperationDependencyDefinerFieldDirectiveResolverInterface $metaFieldDirectiveResolver
     */
    public function addOperationDependencyDefinerFieldDirectiveResolver($metaFieldDirectiveResolver) : void;
    /**
     * @return array<string,OperationDependencyDefinerFieldDirectiveResolverInterface>
     */
    public function getOperationDependencyDefinerFieldDirectiveResolvers() : array;
    /**
     * @param string $directiveName
     */
    public function getOperationDependencyDefinerFieldDirectiveResolver($directiveName) : ?OperationDependencyDefinerFieldDirectiveResolverInterface;
}
