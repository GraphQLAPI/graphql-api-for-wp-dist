<?php

declare (strict_types=1);
namespace PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue;

use PoP\GraphQLParser\Exception\InvalidRequestException;
use PoP\GraphQLParser\FeedbackItemProviders\GraphQLSpecErrorFeedbackItemProvider;
use PoP\GraphQLParser\Spec\Parser\Ast\AbstractAst;
use PoP\GraphQLParser\Spec\Parser\Ast\Variable;
use PoP\GraphQLParser\Spec\Parser\Location;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\Root\Services\StandaloneServiceTrait;
class VariableReference extends AbstractAst implements \PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\VariableReferenceInterface
{
    use StandaloneServiceTrait;
    /**
     * @readonly
     * @var string
     */
    protected $name;
    /**
     * @readonly
     * @var \PoP\GraphQLParser\Spec\Parser\Ast\Variable|null
     */
    protected $variable;
    public function __construct(string $name, ?Variable $variable, Location $location)
    {
        $this->name = $name;
        $this->variable = $variable;
        parent::__construct($location);
    }
    protected function doAsQueryString() : string
    {
        return \sprintf('$%s', $this->name);
    }
    protected function doAsASTNodeString() : string
    {
        return \sprintf('$%s', $this->name);
    }
    public function getVariable() : ?Variable
    {
        return $this->variable;
    }
    public function getName() : string
    {
        return $this->name;
    }
    /**
     * Get the value from the context or from the variable
     *
     * @throws InvalidRequestException
     * @return mixed
     */
    public function getValue()
    {
        if ($this->variable === null) {
            throw new InvalidRequestException(new FeedbackItemResolution(GraphQLSpecErrorFeedbackItemProvider::class, GraphQLSpecErrorFeedbackItemProvider::E_5_8_3, [$this->name]), $this);
        }
        return $this->variable->getValue();
    }
    /**
     * Indicate if a field equals another one based on its properties,
     * not on its object hash ID.
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\VariableReference $variableReference
     */
    public function isEquivalentTo($variableReference) : bool
    {
        return $this->getName() === $variableReference->getName();
    }
}
