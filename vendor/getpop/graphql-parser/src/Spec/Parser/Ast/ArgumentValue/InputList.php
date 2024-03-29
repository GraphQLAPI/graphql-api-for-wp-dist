<?php

declare (strict_types=1);
namespace PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue;

use PoP\GraphQLParser\Spec\Parser\Ast\AbstractAst;
use PoP\GraphQLParser\Spec\Parser\Ast\WithAstValueInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\WithValueInterface;
use PoP\GraphQLParser\Spec\Parser\Location;
use PoP\Root\Exception\ShouldNotHappenException;
use stdClass;
class InputList extends AbstractAst implements \PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\ArgumentValueAstInterface, WithAstValueInterface
{
    /**
     * @var mixed[]
     */
    protected $list;
    /**
     * @param mixed[] $list Elements inside can be WithValueInterface or native types (array, int, string, etc)
     */
    public function __construct(array $list, Location $location)
    {
        $this->list = $list;
        parent::__construct($location);
    }
    protected function doAsQueryString() : string
    {
        return $this->getGraphQLQueryStringFormatter()->getListAsQueryString($this->list);
    }
    protected function doAsASTNodeString() : string
    {
        return $this->getGraphQLQueryStringFormatter()->getListAsQueryString($this->list);
    }
    /**
     * Transform from Ast to actual value.
     * Eg: replace VariableReferences with their value,
     * nested InputObjects with stdClass, etc
     *
     * @return mixed[]
     */
    public final function getValue()
    {
        $list = [];
        foreach ($this->list as $key => $value) {
            if ($value instanceof WithValueInterface) {
                $list[$key] = $value->getValue();
                continue;
            }
            $list[$key] = $value;
        }
        return $list;
    }
    /**
     * @return mixed[]
     */
    public function getAstValue()
    {
        return $this->list;
    }
    /**
     * Indicate if a field equals another one based on its properties,
     * not on its object hash ID.
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\InputList $inputList
     */
    public function isEquivalentTo($inputList) : bool
    {
        $thisInputListValue = $this->getAstValue();
        $againstInputListValue = $inputList->getAstValue();
        $thisInputListValueCount = \count($thisInputListValue);
        if ($thisInputListValueCount !== \count($againstInputListValue)) {
            return \false;
        }
        for ($i = 0; $i < $thisInputListValueCount; $i++) {
            $thisInputListElemValue = $thisInputListValue[$i];
            $againstInputListElemValue = $againstInputListValue[$i];
            if ($thisInputListElemValue === null && $againstInputListElemValue !== null || $thisInputListElemValue !== null && $againstInputListElemValue === null) {
                return \false;
            }
            if (\is_object($thisInputListElemValue) && !\is_object($againstInputListElemValue) || !\is_object($thisInputListElemValue) && \is_object($againstInputListElemValue)) {
                return \false;
            }
            if (\is_object($thisInputListElemValue) && !$thisInputListElemValue instanceof stdClass) {
                if (\get_class($thisInputListElemValue) !== \get_class($againstInputListElemValue)) {
                    return \false;
                }
                /**
                 * Call ->isEquivalentTo depending on the type of object
                 */
                if ($thisInputListElemValue instanceof \PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\InputList) {
                    /** @var InputList */
                    $againstInputList = $againstInputListElemValue;
                    if (!$thisInputListElemValue->isEquivalentTo($againstInputList)) {
                        return \false;
                    }
                    continue;
                }
                if ($thisInputListElemValue instanceof \PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\InputObject) {
                    /** @var InputObject */
                    $inputObject = $againstInputListElemValue;
                    if (!$thisInputListElemValue->isEquivalentTo($inputObject)) {
                        return \false;
                    }
                    continue;
                }
                if ($thisInputListElemValue instanceof \PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Enum) {
                    /** @var Enum */
                    $enum = $againstInputListElemValue;
                    if (!$thisInputListElemValue->isEquivalentTo($enum)) {
                        return \false;
                    }
                    continue;
                }
                if ($thisInputListElemValue instanceof \PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal) {
                    /** @var Literal */
                    $literal = $againstInputListElemValue;
                    if (!$thisInputListElemValue->isEquivalentTo($literal)) {
                        return \false;
                    }
                    continue;
                }
                if ($thisInputListElemValue instanceof \PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\VariableReference) {
                    /** @var VariableReference */
                    $variableReference = $againstInputListElemValue;
                    if (!$thisInputListElemValue->isEquivalentTo($variableReference)) {
                        return \false;
                    }
                    continue;
                }
                throw new ShouldNotHappenException(\sprintf($this->__('Cannot recognize the type of the object, of class \'%s\'', 'graphql-parser'), \get_class($thisInputListElemValue)));
            }
            /**
             * The element is a native type (bool, string, int, or float)
             */
            if ($thisInputListElemValue !== $againstInputListElemValue) {
                return \false;
            }
        }
        return \true;
    }
}
