<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use GraphQLByPoP\GraphQLServer\Facades\Registries\SchemaDefinitionReferenceRegistryFacade;
abstract class AbstractSchemaDefinitionReferenceObject implements \GraphQLByPoP\GraphQLServer\ObjectModels\SchemaDefinitionReferenceObjectInterface
{
    /**
     * @var string
     */
    protected $id;
    /**
     * @var array<string,mixed>
     */
    protected $schemaDefinition;
    /**
     * @var array<string, mixed>
     */
    protected $fullSchemaDefinition;
    /**
     * @var string[]
     */
    protected $schemaDefinitionPath;
    /**
     * Build a new Schema Definition Reference Object
     * @param array<string,mixed> $fullSchemaDefinition
     * @param string[] $schemaDefinitionPath
     */
    public function __construct(array &$fullSchemaDefinition, array $schemaDefinitionPath)
    {
        /** @var array<string,mixed> */
        $this->fullSchemaDefinition = $fullSchemaDefinition;
        /** @var string[] */
        $this->schemaDefinitionPath = $schemaDefinitionPath;
        // Retrieve this element's schema definition by iterating down its path starting from the root of the full schema definition
        $schemaDefinitionPointer =& $fullSchemaDefinition;
        foreach ($schemaDefinitionPath as $pathLevel) {
            $schemaDefinitionPointer =& $schemaDefinitionPointer[$pathLevel];
        }
        $this->schemaDefinition = $schemaDefinitionPointer;
        // Register the object, and get back its ID
        $schemaDefinitionReferenceRegistry = SchemaDefinitionReferenceRegistryFacade::getInstance();
        $this->id = $schemaDefinitionReferenceRegistry->registerSchemaDefinitionReferenceObject($this);
    }
    /**
     * @return array<string,mixed>
     */
    public function getSchemaDefinition() : array
    {
        return $this->schemaDefinition;
    }
    /**
     * @return string[]
     */
    public function getSchemaDefinitionPath() : array
    {
        return $this->schemaDefinitionPath;
    }
    public function getID() : string
    {
        return $this->id;
    }
}
