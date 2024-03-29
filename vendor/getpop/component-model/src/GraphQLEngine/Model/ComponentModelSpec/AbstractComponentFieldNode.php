<?php

declare (strict_types=1);
namespace PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
abstract class AbstractComponentFieldNode implements \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\ComponentFieldNodeInterface
{
    /**
     * @var \PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface
     */
    protected $field;
    public function __construct(FieldInterface $field)
    {
        $this->field = $field;
    }
    /**
     * Allow doing `array_unique` based on the underlying Field
     */
    public function __toString() : string
    {
        return $this->field->getUniqueID();
    }
    public function getField() : FieldInterface
    {
        return $this->field;
    }
    /**
     * A Field that appears earlier in the GraphQL query
     * must be resolved first.
     * @param \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\ComponentFieldNodeInterface $againstComponentFieldNode
     */
    public function sortAgainst($againstComponentFieldNode) : int
    {
        $location = $this->getField()->getLocation();
        $againstLocation = $againstComponentFieldNode->getField()->getLocation();
        if ($location->getLine() > $againstLocation->getLine()) {
            return 1;
        }
        if ($location->getLine() < $againstLocation->getLine()) {
            return -1;
        }
        if ($location->getColumn() > $againstLocation->getColumn()) {
            return 1;
        }
        if ($location->getColumn() < $againstLocation->getColumn()) {
            return -1;
        }
        return 0;
    }
}
