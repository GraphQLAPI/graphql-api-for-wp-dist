<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use PoP\ComponentModel\Schema\SchemaDefinition;
abstract class AbstractNamedType extends \GraphQLByPoP\GraphQLServer\ObjectModels\AbstractSchemaDefinitionReferenceObject implements \GraphQLByPoP\GraphQLServer\ObjectModels\NamedTypeInterface
{
    /**
     * @var \GraphQLByPoP\GraphQLServer\ObjectModels\NamedTypeExtensions
     */
    protected $namedTypeExtensions;
    /**
     * @param array<string,mixed> $fullSchemaDefinition
     * @param string[] $schemaDefinitionPath
     */
    public function __construct(array &$fullSchemaDefinition, array $schemaDefinitionPath)
    {
        parent::__construct($fullSchemaDefinition, $schemaDefinitionPath);
        /** @var string[] */
        $namedTypeExtensionsSchemaDefinitionPath = \array_merge($schemaDefinitionPath, [SchemaDefinition::EXTENSIONS]);
        $this->namedTypeExtensions = new \GraphQLByPoP\GraphQLServer\ObjectModels\NamedTypeExtensions($fullSchemaDefinition, $namedTypeExtensionsSchemaDefinitionPath);
    }
    public function getNamespacedName() : string
    {
        return $this->schemaDefinition[SchemaDefinition::EXTENSIONS][SchemaDefinition::NAMESPACED_NAME];
    }
    public function getElementName() : string
    {
        return $this->schemaDefinition[SchemaDefinition::EXTENSIONS][SchemaDefinition::ELEMENT_NAME];
    }
    public function getName() : string
    {
        return $this->schemaDefinition[SchemaDefinition::NAME];
    }
    public function getDescription() : ?string
    {
        return $this->schemaDefinition[SchemaDefinition::DESCRIPTION] ?? null;
    }
    public function getExtensions() : \GraphQLByPoP\GraphQLServer\ObjectModels\NamedTypeExtensions
    {
        return $this->namedTypeExtensions;
    }
}
