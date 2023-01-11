<?php

declare (strict_types=1);
namespace PoP\GraphQLParser\Spec\Execution;

class Context
{
    /**
     * @readonly
     * @var string
     */
    private $operationName;
    /**
     * @var array<string, mixed>
     * @readonly
     */
    private $variableValues = [];
    /**
     * @param array<string,mixed> $variableValues
     */
    public function __construct(?string $operationName = null, array $variableValues = [])
    {
        $this->variableValues = $variableValues;
        $this->operationName = $operationName !== null ? \trim($operationName) : '';
    }
    public function getOperationName() : string
    {
        return $this->operationName;
    }
    /**
     * @return array<string,mixed>
     */
    public function getVariableValues() : array
    {
        return $this->variableValues;
    }
    /**
     * @param string $variableName
     */
    public function hasVariableValue($variableName) : bool
    {
        return \array_key_exists($variableName, $this->variableValues);
    }
    /**
     * @return mixed
     * @param string $variableName
     */
    public function getVariableValue($variableName)
    {
        return $this->variableValues[$variableName] ?? null;
    }
}
