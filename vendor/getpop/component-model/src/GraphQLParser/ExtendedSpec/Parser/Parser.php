<?php

declare (strict_types=1);
namespace PoP\ComponentModel\GraphQLParser\ExtendedSpec\Parser;

use PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface;
use PoP\ComponentModel\DirectiveResolvers\DynamicVariableDefinerFieldDirectiveResolverInterface;
use PoP\ComponentModel\DirectiveResolvers\MetaFieldDirectiveResolverInterface;
use PoP\ComponentModel\ExtendedSpec\Parser\Ast\Document;
use PoP\ComponentModel\Registries\DirectiveRegistryInterface;
use PoP\ComponentModel\Registries\DynamicVariableDefinerDirectiveRegistryInterface;
use PoP\ComponentModel\Registries\MetaDirectiveRegistryInterface;
use PoP\GraphQLParser\ExtendedSpec\Parser\AbstractParser;
use PoP\GraphQLParser\ExtendedSpec\Parser\Ast\AbstractDocument;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\Fragment;
use PoP\GraphQLParser\Spec\Parser\Ast\OperationInterface;
use PoP\Root\Facades\Instances\InstanceManagerFacade;
class Parser extends AbstractParser
{
    /**
     * @var \PoP\ComponentModel\Registries\MetaDirectiveRegistryInterface|null
     */
    private $metaDirectiveRegistry;
    /**
     * @var \PoP\ComponentModel\Registries\DynamicVariableDefinerDirectiveRegistryInterface|null
     */
    private $dynamicVariableDefinerDirectiveRegistry;
    /**
     * @var \PoP\ComponentModel\Registries\DirectiveRegistryInterface|null
     */
    private $directiveRegistry;
    /**
     * @param \PoP\ComponentModel\Registries\MetaDirectiveRegistryInterface $metaDirectiveRegistry
     */
    public final function setMetaDirectiveRegistry($metaDirectiveRegistry) : void
    {
        $this->metaDirectiveRegistry = $metaDirectiveRegistry;
    }
    protected final function getMetaDirectiveRegistry() : MetaDirectiveRegistryInterface
    {
        /** @var MetaDirectiveRegistryInterface */
        return $this->metaDirectiveRegistry = $this->metaDirectiveRegistry ?? InstanceManagerFacade::getInstance()->getInstance(MetaDirectiveRegistryInterface::class);
    }
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
     * @param \PoP\ComponentModel\Registries\DirectiveRegistryInterface $directiveRegistry
     */
    public final function setDirectiveRegistry($directiveRegistry) : void
    {
        $this->directiveRegistry = $directiveRegistry;
    }
    protected final function getDirectiveRegistry() : DirectiveRegistryInterface
    {
        /** @var DirectiveRegistryInterface */
        return $this->directiveRegistry = $this->directiveRegistry ?? InstanceManagerFacade::getInstance()->getInstance(DirectiveRegistryInterface::class);
    }
    /**
     * @param string $directiveName
     */
    protected function isMetaDirective($directiveName) : bool
    {
        $metaFieldDirectiveResolver = $this->getMetaFieldDirectiveResolver($directiveName);
        return $metaFieldDirectiveResolver !== null;
    }
    /**
     * @param string $directiveName
     */
    protected function getMetaFieldDirectiveResolver($directiveName) : ?MetaFieldDirectiveResolverInterface
    {
        return $this->getMetaDirectiveRegistry()->getMetaFieldDirectiveResolver($directiveName);
    }
    /**
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\Directive $directive
     */
    protected function getAffectDirectivesUnderPosArgument($directive) : ?Argument
    {
        /** @var MetaFieldDirectiveResolverInterface */
        $metaFieldDirectiveResolver = $this->getMetaFieldDirectiveResolver($directive->getName());
        $affectDirectivesUnderPosArgumentName = $metaFieldDirectiveResolver->getAffectDirectivesUnderPosArgumentName();
        foreach ($directive->getArguments() as $argument) {
            if ($argument->getName() !== $affectDirectivesUnderPosArgumentName) {
                continue;
            }
            return $argument;
        }
        return null;
    }
    /**
     * @return int[]
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\Directive $directive
     */
    protected function getAffectDirectivesUnderPosArgumentDefaultValue($directive) : array
    {
        /** @var MetaFieldDirectiveResolverInterface */
        $metaFieldDirectiveResolver = $this->getMetaFieldDirectiveResolver($directive->getName());
        return $metaFieldDirectiveResolver->getAffectDirectivesUnderPosArgumentDefaultValue();
    }
    /**
     * @param OperationInterface[] $operations
     * @param Fragment[] $fragments
     */
    protected function createDocumentInstance($operations, $fragments) : AbstractDocument
    {
        return new Document($operations, $fragments);
    }
    /**
     * @param string $directiveName
     */
    protected function getFieldDirectiveResolver($directiveName) : ?FieldDirectiveResolverInterface
    {
        return $this->getDirectiveRegistry()->getFieldDirectiveResolver($directiveName);
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
    protected function getAffectAdditionalFieldsUnderPosArgumentName($directive) : ?string
    {
        $directiveResolver = $this->getFieldDirectiveResolver($directive->getName());
        if ($directiveResolver === null) {
            return null;
        }
        return $directiveResolver->getAffectAdditionalFieldsUnderPosArgumentName();
    }
    /**
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\Directive $directive
     */
    protected function mustResolveDynamicVariableOnObject($directive) : ?bool
    {
        $dynamicVariableDefinerFieldDirectiveResolver = $this->getDynamicVariableDefinerFieldDirectiveResolver($directive);
        if ($dynamicVariableDefinerFieldDirectiveResolver === null) {
            return null;
        }
        return $dynamicVariableDefinerFieldDirectiveResolver->mustResolveDynamicVariableOnObject();
    }
}
