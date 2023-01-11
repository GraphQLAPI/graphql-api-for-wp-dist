<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use PoP\ComponentModel\Schema\SchemaDefinition;
class InputObjectType extends \GraphQLByPoP\GraphQLServer\ObjectModels\AbstractNamedType
{
    /**
     * @var InputValue[]
     */
    protected $inputValues;
    /**
     * @param array<string,mixed> $fullSchemaDefinition
     * @param string[] $schemaDefinitionPath
     */
    public function __construct(array &$fullSchemaDefinition, array $schemaDefinitionPath)
    {
        parent::__construct($fullSchemaDefinition, $schemaDefinitionPath);
        $this->initInputValues($fullSchemaDefinition, $schemaDefinitionPath);
    }
    /**
     * @param array<string,mixed> $fullSchemaDefinition
     * @param string[] $schemaDefinitionPath
     */
    protected function initInputValues(&$fullSchemaDefinition, $schemaDefinitionPath) : void
    {
        $this->inputValues = [];
        if ($inputValues = $this->schemaDefinition[SchemaDefinition::INPUT_FIELDS] ?? null) {
            /** @var string $inputValueName */
            foreach (\array_keys($inputValues) as $inputValueName) {
                /** @var string[] */
                $inputValueSchemaDefinitionPath = \array_merge($schemaDefinitionPath, [SchemaDefinition::INPUT_FIELDS, $inputValueName]);
                $this->inputValues[] = new \GraphQLByPoP\GraphQLServer\ObjectModels\InputValue($fullSchemaDefinition, $inputValueSchemaDefinitionPath);
            }
        }
    }
    public function getKind() : string
    {
        return \GraphQLByPoP\GraphQLServer\ObjectModels\TypeKinds::INPUT_OBJECT;
    }
    /**
     * @return InputValue[]
     */
    public function getInputFields() : array
    {
        return $this->inputValues;
    }
    /**
     * @return string[]
     */
    public function getInputFieldIDs() : array
    {
        return \array_map(function (\GraphQLByPoP\GraphQLServer\ObjectModels\InputValue $inputValue) : string {
            return $inputValue->getID();
        }, $this->getInputFields());
    }
}
