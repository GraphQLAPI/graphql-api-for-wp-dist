<?php

declare (strict_types=1);
namespace PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue;

use PoP\GraphQLParser\Spec\Parser\Ast\AbstractAst;
use PoP\GraphQLParser\Spec\Parser\Location;
class Enum extends AbstractAst implements \PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\ArgumentValueAstInterface
{
    /**
     * @var string
     */
    protected $enumValue;
    public function __construct(string $enumValue, Location $location)
    {
        $this->enumValue = $enumValue;
        parent::__construct($location);
    }
    protected function doAsQueryString() : string
    {
        return $this->enumValue;
    }
    protected function doAsASTNodeString() : string
    {
        return $this->enumValue;
    }
    /**
     * @return string
     */
    public function getValue()
    {
        return $this->enumValue;
    }
    /**
     * Indicate if a field equals another one based on its properties,
     * not on its object hash ID.
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Enum $enum
     */
    public function isEquivalentTo($enum) : bool
    {
        return $this->getValue() === $enum->getValue();
    }
}
