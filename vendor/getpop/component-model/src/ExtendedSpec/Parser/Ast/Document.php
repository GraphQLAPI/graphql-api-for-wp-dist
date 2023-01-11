<?php

declare (strict_types=1);
namespace PoP\ComponentModel\ExtendedSpec\Parser\Ast;

use PoP\ComponentModel\DirectiveResolvers\DynamicVariableDefinerFieldDirectiveResolverInterface;
use PoP\ComponentModel\Registries\DynamicVariableDefinerDirectiveRegistryInterface;
use PoP\ComponentModel\DirectiveResolvers\OperationDependencyDefinerFieldDirectiveResolverInterface;
use PoP\ComponentModel\Registries\OperationDependencyDefinerDirectiveRegistryInterface;
use PoP\GraphQLParser\ExtendedSpec\Parser\Ast\AbstractDocument;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\Root\Facades\Instances\InstanceManagerFacade;
class Document extends AbstractDocument
{
    /**
     * @var \PoP\ComponentModel\Registries\DynamicVariableDefinerDirectiveRegistryInterface|null
     */
    private $dynamicVariableDefinerDirectiveRegistry;
    /**
     * @var \PoP\ComponentModel\Registries\OperationDependencyDefinerDirectiveRegistryInterface|null
     */
    private $operationDependencyDefinerDirectiveRegistry;
    /**
     * @param \PoP\ComponentModel\Registries\DynamicVariableDefinerDirectiveRegistryInterface $dynamicVariableDefinerDirectiveRegistry
     */
    public final function setDynamicVariableDefinerDirectiveRegistry($dynamicVariableDefinerDirectiveRegistry) : void
    {
        $this->dynamicVariableDefinerDirectiveRegistry = $dynamicVariableDefinerDirectiveRegistry;
    }
    protected final function getDynamicVariableDefinerDirectiveRegistry() : DynamicVariableDefinerDirectiveRegistryInterface
    {
        /** @var DynamicVariableDefinerDirectiveRegistryInterface */
        return $this->dynamicVariableDefinerDirectiveRegistry = $this->dynamicVariableDefinerDirectiveRegistry ?? InstanceManagerFacade::getInstance()->getInstance(DynamicVariableDefinerDirectiveRegistryInterface::class);
    }
    /**
     * @param \PoP\ComponentModel\Registries\OperationDependencyDefinerDirectiveRegistryInterface $operationDependencyDefinerDirectiveRegistry
     */
    public final function setOperationDependencyDefinerDirectiveRegistry($operationDependencyDefinerDirectiveRegistry) : void
    {
        $this->operationDependencyDefinerDirectiveRegistry = $operationDependencyDefinerDirectiveRegistry;
    }
    protected final function getOperationDependencyDefinerDirectiveRegistry() : OperationDependencyDefinerDirectiveRegistryInterface
    {
        /** @var OperationDependencyDefinerDirectiveRegistryInterface */
        return $this->operationDependencyDefinerDirectiveRegistry = $this->operationDependencyDefinerDirectiveRegistry ?? InstanceManagerFacade::getInstance()->getInstance(OperationDependencyDefinerDirectiveRegistryInterface::class);
    }
    /**
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\Directive $directive
     */
    protected function isDynamicVariableDefinerDirective($directive) : bool
    {
        return $this->getDynamicVariableDefinerFieldDirectiveResolver($directive) !== null;
    }
    /**
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\Directive $directive
     */
    protected function getDynamicVariableDefinerFieldDirectiveResolver($directive) : ?DynamicVariableDefinerFieldDirectiveResolverInterface
    {
        return $this->getDynamicVariableDefinerDirectiveRegistry()->getDynamicVariableDefinerFieldDirectiveResolver($directive->getName());
    }
    /**
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\Directive $directive
     */
    protected function getExportUnderVariableNameArgument($directive) : ?Argument
    {
        $dynamicVariableDefinerFieldDirectiveResolver = $this->getDynamicVariableDefinerFieldDirectiveResolver($directive);
        if ($dynamicVariableDefinerFieldDirectiveResolver === null) {
            return null;
        }
        $exportUnderVariableNameArgumentName = $dynamicVariableDefinerFieldDirectiveResolver->getExportUnderVariableNameArgumentName();
        return $directive->getArgument($exportUnderVariableNameArgumentName);
    }
    /**
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\Directive $directive
     */
    protected function isOperationDependencyDefinerDirective($directive) : bool
    {
        return $this->getOperationDependencyDefinerFieldDirectiveResolver($directive) !== null;
    }
    /**
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\Directive $directive
     */
    protected function getOperationDependencyDefinerFieldDirectiveResolver($directive) : ?OperationDependencyDefinerFieldDirectiveResolverInterface
    {
        return $this->getOperationDependencyDefinerDirectiveRegistry()->getOperationDependencyDefinerFieldDirectiveResolver($directive->getName());
    }
    /**
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\Directive $directive
     */
    protected function getProvideDependedUponOperationNamesArgument($directive) : ?Argument
    {
        $operationDependencyDefinerFieldDirectiveResolver = $this->getOperationDependencyDefinerFieldDirectiveResolver($directive);
        if ($operationDependencyDefinerFieldDirectiveResolver === null) {
            return null;
        }
        $provideDependedUponOperationNamesArgumentName = $operationDependencyDefinerFieldDirectiveResolver->getProvideDependedUponOperationNamesArgumentName();
        return $directive->getArgument($provideDependedUponOperationNamesArgumentName);
    }
}
