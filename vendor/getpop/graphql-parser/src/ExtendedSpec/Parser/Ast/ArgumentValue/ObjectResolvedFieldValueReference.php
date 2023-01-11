<?php

declare (strict_types=1);
namespace PoP\GraphQLParser\ExtendedSpec\Parser\Ast\ArgumentValue;

use PoP\GraphQLParser\ExtendedSpec\Execution\ObjectFieldValuePromise;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Location;
class ObjectResolvedFieldValueReference extends \PoP\GraphQLParser\ExtendedSpec\Parser\Ast\ArgumentValue\AbstractRuntimeVariableReference
{
    /**
     * @var \PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface
     */
    protected $field;
    public function __construct(string $name, FieldInterface $field, Location $location)
    {
        $this->field = $field;
        parent::__construct($name, $location);
    }
    /**
     * @return mixed
     */
    public function getValue()
    {
        return new ObjectFieldValuePromise($this->field);
    }
}
