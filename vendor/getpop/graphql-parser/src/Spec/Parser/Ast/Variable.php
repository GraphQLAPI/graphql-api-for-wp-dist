<?php

declare (strict_types=1);
namespace PoP\GraphQLParser\Spec\Parser\Ast;

use PoP\GraphQLParser\Exception\InvalidRequestException;
use PoP\GraphQLParser\FeedbackItemProviders\GraphQLSpecErrorFeedbackItemProvider;
use PoP\GraphQLParser\Spec\Execution\Context;
use PoP\GraphQLParser\Spec\Parser\Ast\AbstractAst;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Enum;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\InputList;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\InputObject;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\WithDirectivesTrait;
use PoP\GraphQLParser\Spec\Parser\Ast\WithValueInterface;
use PoP\GraphQLParser\Spec\Parser\Location;
use PoP\Root\Exception\ShouldNotHappenException;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\Root\Services\StandaloneServiceTrait;
class Variable extends AbstractAst implements WithValueInterface
{
    use StandaloneServiceTrait;
    use WithDirectivesTrait;
    /**
     * @var \PoP\GraphQLParser\Spec\Execution\Context|null
     */
    protected $context;
    /**
     * @var bool
     */
    protected $hasDefaultValue = \false;
    /**
     * @var \PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\InputList|\PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\InputObject|\PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal|\PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Enum|null
     */
    protected $defaultValueAST = null;
    /**
     * @readonly
     * @var string
     */
    protected $name;
    /**
     * @readonly
     * @var string
     */
    protected $type;
    /**
     * @readonly
     * @var bool
     */
    protected $isRequired;
    /**
     * @readonly
     * @var bool
     */
    protected $isArray;
    /**
     * @readonly
     * @var bool
     */
    protected $isArrayElementRequired;
    /**
     * @readonly
     * @var bool
     */
    protected $isArrayOfArrays;
    /**
     * @readonly
     * @var bool
     */
    protected $isArrayOfArraysElementRequired;
    /**
     * @param Directive[] $directives
     */
    public function __construct(string $name, string $type, bool $isRequired, bool $isArray, bool $isArrayElementRequired, bool $isArrayOfArrays, bool $isArrayOfArraysElementRequired, array $directives, Location $location)
    {
        $this->name = $name;
        $this->type = $type;
        $this->isRequired = $isRequired;
        $this->isArray = $isArray;
        $this->isArrayElementRequired = $isArrayElementRequired;
        $this->isArrayOfArrays = $isArrayOfArrays;
        $this->isArrayOfArraysElementRequired = $isArrayOfArraysElementRequired;
        parent::__construct($location);
        $this->setDirectives($directives);
    }
    protected function doAsQueryString() : string
    {
        $strType = $this->type;
        if ($this->isArray) {
            if ($this->isArrayOfArrays) {
                if ($this->isArrayOfArraysElementRequired) {
                    $strType .= '!';
                }
                $strType = \sprintf('[%s]', $strType);
            }
            if ($this->isArrayElementRequired) {
                $strType .= '!';
            }
            $strType = \sprintf('[%s]', $strType);
        }
        if ($this->isRequired) {
            $strType .= '!';
        }
        $defaultValue = '';
        if ($this->hasDefaultValue()) {
            /** @var InputList|InputObject|Literal|Enum */
            $defaultValueAST = $this->getDefaultValueAST();
            $defaultValue = \sprintf(' = %s', $defaultValueAST->asQueryString());
        }
        // Generate the string for directives
        $strVariableDirectives = '';
        if ($this->directives !== []) {
            $strDirectives = [];
            foreach ($this->directives as $directive) {
                $strDirectives[] = $directive->asQueryString();
            }
            $strVariableDirectives = \sprintf(' %s', \implode(' ', $strDirectives));
        }
        return \sprintf('$%s: %s%s%s', $this->name, $strType, $defaultValue, $strVariableDirectives);
    }
    protected function doAsASTNodeString() : string
    {
        $strType = $this->type;
        if ($this->isArray) {
            if ($this->isArrayOfArrays) {
                if ($this->isArrayOfArraysElementRequired) {
                    $strType .= '!';
                }
                $strType = \sprintf('[%s]', $strType);
            }
            if ($this->isArrayElementRequired) {
                $strType .= '!';
            }
            $strType = \sprintf('[%s]', $strType);
        }
        if ($this->isRequired) {
            $strType .= '!';
        }
        $defaultValue = '';
        if ($this->hasDefaultValue()) {
            /** @var InputList|InputObject|Literal|Enum */
            $defaultValueAST = $this->getDefaultValueAST();
            $defaultValue = \sprintf(' = %s', $defaultValueAST->asQueryString());
        }
        return \sprintf('$%s: %s%s', $this->name, $strType, $defaultValue);
    }
    /**
     * @param \PoP\GraphQLParser\Spec\Execution\Context|null $context
     */
    public function setContext($context) : void
    {
        $this->context = $context;
    }
    public function getName() : string
    {
        return $this->name;
    }
    public function getTypeName() : string
    {
        return $this->type;
    }
    public function isRequired() : bool
    {
        return $this->isRequired;
    }
    public function isArray() : bool
    {
        return $this->isArray;
    }
    public function isArrayElementRequired() : bool
    {
        return $this->isArrayElementRequired;
    }
    public function isArrayOfArrays() : bool
    {
        return $this->isArrayOfArrays;
    }
    public function isArrayOfArraysElementRequired() : bool
    {
        return $this->isArrayOfArraysElementRequired;
    }
    public function hasDefaultValue() : bool
    {
        return $this->hasDefaultValue;
    }
    /**
     * @return mixed
     */
    public function getDefaultValue()
    {
        return ($defaultValueAST = $this->defaultValueAST) ? $defaultValueAST->getValue() : null;
    }
    /**
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\InputList|\PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\InputObject|\PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal|\PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Enum|null $defaultValueAST
     */
    public function setDefaultValueAST($defaultValueAST) : void
    {
        $this->hasDefaultValue = $defaultValueAST !== null;
        $this->defaultValueAST = $defaultValueAST;
    }
    /**
     * Get the value from the context or from the variable
     *
     * @return InputList|InputObject|Literal|Enum|null
     * @throws InvalidRequestException
     * @throws ShouldNotHappenException When context not set
     */
    public function getValue()
    {
        if ($this->context === null) {
            throw new ShouldNotHappenException(\sprintf($this->__('Context has not been set for Variable object (with name \'%s\')', 'graphql-server'), $this->name));
        }
        if ($this->context->hasVariableValue($this->name)) {
            return $this->context->getVariableValue($this->name);
        }
        if ($this->hasDefaultValue()) {
            return $this->getDefaultValue();
        }
        if ($this->isRequired()) {
            throw new InvalidRequestException(new FeedbackItemResolution(GraphQLSpecErrorFeedbackItemProvider::class, GraphQLSpecErrorFeedbackItemProvider::E_5_8_5, [$this->name]), $this);
        }
        return null;
    }
    /**
     * @return \PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\InputList|\PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\InputObject|\PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal|\PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Enum|null
     */
    public function getDefaultValueAST()
    {
        return $this->defaultValueAST;
    }
}
