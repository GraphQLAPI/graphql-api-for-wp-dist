<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use PoP\ComponentModel\Schema\SchemaDefinition;
class EnumType extends \GraphQLByPoP\GraphQLServer\ObjectModels\AbstractNamedType
{
    /**
     * @var EnumValue[]
     */
    protected $enumValues;
    /**
     * @param array<string,mixed> $fullSchemaDefinition
     * @param string[] $schemaDefinitionPath
     */
    public function __construct(array &$fullSchemaDefinition, array $schemaDefinitionPath)
    {
        parent::__construct($fullSchemaDefinition, $schemaDefinitionPath);
        $this->initEnumValues($fullSchemaDefinition, $schemaDefinitionPath);
    }
    /**
     * @param array<string,mixed> $fullSchemaDefinition
     * @param string[] $schemaDefinitionPath
     */
    protected function initEnumValues(&$fullSchemaDefinition, $schemaDefinitionPath) : void
    {
        $this->enumValues = [];
        $enumItems = $this->schemaDefinition[SchemaDefinition::ITEMS];
        /** @var string $enumValue */
        foreach (\array_keys($enumItems) as $enumValue) {
            /** @var string[] */
            $enumValueSchemaDefinitionPath = \array_merge($schemaDefinitionPath, [SchemaDefinition::ITEMS, $enumValue]);
            $this->enumValues[] = new \GraphQLByPoP\GraphQLServer\ObjectModels\EnumValue($fullSchemaDefinition, $enumValueSchemaDefinitionPath);
        }
    }
    public function getKind() : string
    {
        return \GraphQLByPoP\GraphQLServer\ObjectModels\TypeKinds::ENUM;
    }
    /**
     * @return EnumValue[]
     * @param bool $includeDeprecated
     */
    public function getEnumValues($includeDeprecated = \false) : array
    {
        return $includeDeprecated ? $this->enumValues : \array_filter($this->enumValues, function (\GraphQLByPoP\GraphQLServer\ObjectModels\EnumValue $enumValue) : bool {
            return !$enumValue->isDeprecated();
        });
    }
    /**
     * @return string[]
     * @param bool $includeDeprecated
     */
    public function getEnumValueIDs($includeDeprecated = \false) : array
    {
        return \array_map(function (\GraphQLByPoP\GraphQLServer\ObjectModels\EnumValue $enumValue) : string {
            return $enumValue->getID();
        }, $this->getEnumValues($includeDeprecated));
    }
}
