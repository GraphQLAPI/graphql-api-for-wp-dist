<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Registries;

use PoP\ComponentModel\DirectiveResolvers\OperationDependencyDefinerFieldDirectiveResolverInterface;
class OperationDependencyDefinerDirectiveRegistry implements \PoP\ComponentModel\Registries\OperationDependencyDefinerDirectiveRegistryInterface
{
    /**
     * @var array<string,OperationDependencyDefinerFieldDirectiveResolverInterface>
     */
    protected $operationDependencyDefinerFieldDirectiveResolvers = [];
    /**
     * @param \PoP\ComponentModel\DirectiveResolvers\OperationDependencyDefinerFieldDirectiveResolverInterface $operationDependencyDefinerFieldDirectiveResolver
     */
    public function addOperationDependencyDefinerFieldDirectiveResolver($operationDependencyDefinerFieldDirectiveResolver) : void
    {
        $this->operationDependencyDefinerFieldDirectiveResolvers[$operationDependencyDefinerFieldDirectiveResolver->getDirectiveName()] = $operationDependencyDefinerFieldDirectiveResolver;
    }
    /**
     * @return array<string,OperationDependencyDefinerFieldDirectiveResolverInterface>
     */
    public function getOperationDependencyDefinerFieldDirectiveResolvers() : array
    {
        return $this->operationDependencyDefinerFieldDirectiveResolvers;
    }
    /**
     * @param string $directiveName
     */
    public function getOperationDependencyDefinerFieldDirectiveResolver($directiveName) : ?OperationDependencyDefinerFieldDirectiveResolverInterface
    {
        return $this->operationDependencyDefinerFieldDirectiveResolvers[$directiveName] ?? null;
    }
}
