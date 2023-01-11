<?php

declare (strict_types=1);
namespace PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue;

use PoP\GraphQLParser\Spec\Parser\Ast\AbstractAst;
use PoP\GraphQLParser\Spec\Parser\Location;
class Literal extends AbstractAst implements \PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\ArgumentValueAstInterface
{
    /**
     * @var string|int|float|bool|null
     */
    protected $value;
    /**
     * @param string|int|float|bool|null $value
     */
    public function __construct($value, Location $location)
    {
        $this->value = $value;
        parent::__construct($location);
    }
    protected function doAsQueryString() : string
    {
        return $this->getGraphQLQueryStringFormatter()->getLiteralAsQueryString($this->value);
    }
    protected function doAsASTNodeString() : string
    {
        return $this->getGraphQLQueryStringFormatter()->getLiteralAsQueryString($this->value);
    }
    /**
     * @return string|int|float|bool|null
     */
    public function getValue()
    {
        return $this->value;
    }
    /**
     * Indicate if a field equals another one based on its properties,
     * not on its object hash ID.
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal $literal
     */
    public function isEquivalentTo($literal) : bool
    {
        return $this->getValue() === $literal->getValue();
    }
}
