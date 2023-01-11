<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use PoPAPI\API\Schema\SchemaDefinition;
class Field extends \GraphQLByPoP\GraphQLServer\ObjectModels\AbstractSchemaDefinitionReferenceObject
{
    use \GraphQLByPoP\GraphQLServer\ObjectModels\HasTypeSchemaDefinitionReferenceTrait;
    use \GraphQLByPoP\GraphQLServer\ObjectModels\HasArgsSchemaDefinitionReferenceTrait;
    /**
     * @var \GraphQLByPoP\GraphQLServer\ObjectModels\FieldExtensions
     */
    protected $fieldExtensions;
    /**
     * @param array<string,mixed> $fullSchemaDefinition
     * @param string[] $schemaDefinitionPath
     */
    public function __construct(array &$fullSchemaDefinition, array $schemaDefinitionPath)
    {
        parent::__construct($fullSchemaDefinition, $schemaDefinitionPath);
        /** @var string[] */
        $fieldExtensionsSchemaDefinitionPath = \array_merge($schemaDefinitionPath, [SchemaDefinition::EXTENSIONS]);
        $this->fieldExtensions = new \GraphQLByPoP\GraphQLServer\ObjectModels\FieldExtensions($fullSchemaDefinition, $fieldExtensionsSchemaDefinitionPath);
        $this->initArgs($fullSchemaDefinition, $schemaDefinitionPath);
    }
    public function getName() : string
    {
        return $this->schemaDefinition[SchemaDefinition::NAME];
    }
    public function getDescription() : ?string
    {
        return $this->schemaDefinition[SchemaDefinition::DESCRIPTION] ?? null;
    }
    public function isDeprecated() : bool
    {
        return $this->schemaDefinition[SchemaDefinition::DEPRECATED] ?? \false;
    }
    public function getDeprecationMessage() : ?string
    {
        return $this->schemaDefinition[SchemaDefinition::DEPRECATION_MESSAGE] ?? null;
    }
    public function getExtensions() : \GraphQLByPoP\GraphQLServer\ObjectModels\FieldExtensions
    {
        return $this->fieldExtensions;
    }
}
