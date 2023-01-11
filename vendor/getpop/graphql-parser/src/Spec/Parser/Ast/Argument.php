<?php

declare (strict_types=1);
namespace PoP\GraphQLParser\Spec\Parser\Ast;

use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Enum;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\InputList;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\InputObject;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\VariableReference;
use PoP\GraphQLParser\Spec\Parser\Location;
use PoP\Root\Exception\ShouldNotHappenException;
class Argument extends \PoP\GraphQLParser\Spec\Parser\Ast\AbstractAst
{
    /**
     * @readonly
     * @var string
     */
    protected $name;
    /**
     * @var \PoP\GraphQLParser\Spec\Parser\Ast\WithValueInterface
     */
    protected $value;
    public function __construct(string $name, \PoP\GraphQLParser\Spec\Parser\Ast\WithValueInterface $value, Location $location)
    {
        $this->name = $name;
        $this->value = $value;
        parent::__construct($location);
    }
    protected function doAsQueryString() : string
    {
        return \sprintf('%s: %s', $this->name, $this->value->asQueryString());
    }
    protected function doAsASTNodeString() : string
    {
        return \sprintf('(%s: %s)', $this->name, $this->value->asQueryString());
    }
    public function getName() : string
    {
        return $this->name;
    }
    public function getValueAST() : \PoP\GraphQLParser\Spec\Parser\Ast\WithValueInterface
    {
        return $this->value;
    }
    /**
     * @return mixed
     */
    public final function getValue()
    {
        return $this->value->getValue();
    }
    /**
     * Indicate if a field equals another one based on its properties,
     * not on its object hash ID.
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\Argument $argument
     */
    public function isEquivalentTo($argument) : bool
    {
        if ($this->getName() !== $argument->getName()) {
            return \false;
        }
        $thisValueAST = $this->getValueAST();
        if (\get_class($thisValueAST) !== \get_class($argument->getValueAST())) {
            return \false;
        }
        /**
         * Call ->isEquivalentTo depending on the type of object
         */
        if ($thisValueAST instanceof InputList) {
            /** @var InputList */
            $inputList = $argument->getValueAST();
            return $thisValueAST->isEquivalentTo($inputList);
        }
        if ($thisValueAST instanceof InputObject) {
            /** @var InputObject */
            $inputObject = $argument->getValueAST();
            return $thisValueAST->isEquivalentTo($inputObject);
        }
        if ($thisValueAST instanceof Enum) {
            /** @var Enum */
            $enum = $argument->getValueAST();
            return $thisValueAST->isEquivalentTo($enum);
        }
        if ($thisValueAST instanceof Literal) {
            /** @var Literal */
            $literal = $argument->getValueAST();
            return $thisValueAST->isEquivalentTo($literal);
        }
        if ($thisValueAST instanceof VariableReference) {
            /** @var VariableReference */
            $variableReference = $argument->getValueAST();
            return $thisValueAST->isEquivalentTo($variableReference);
        }
        throw new ShouldNotHappenException(\sprintf($this->__('Cannot recognize the type of the object, of class \'%s\'', 'graphql-parser'), \get_class($thisValueAST)));
    }
}
