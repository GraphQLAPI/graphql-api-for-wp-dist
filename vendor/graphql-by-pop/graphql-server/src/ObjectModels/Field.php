<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use PoP\API\Schema\SchemaDefinition;
use GraphQLByPoP\GraphQLServer\ObjectModels\HasArgsSchemaDefinitionReferenceTrait;
use GraphQLByPoP\GraphQLServer\ObjectModels\HasTypeSchemaDefinitionReferenceTrait;
class Field extends \GraphQLByPoP\GraphQLServer\ObjectModels\AbstractSchemaDefinitionReferenceObject
{
    use HasTypeSchemaDefinitionReferenceTrait;
    use HasArgsSchemaDefinitionReferenceTrait;
    public function __construct(array &$fullSchemaDefinition, array $schemaDefinitionPath, array $customDefinition = [])
    {
        parent::__construct($fullSchemaDefinition, $schemaDefinitionPath, $customDefinition);
        $this->initArgs($fullSchemaDefinition, $schemaDefinitionPath);
    }
    public function initializeTypeDependencies() : void
    {
        $this->initType();
        $this->initializeArgsTypeDependencies();
    }
    public function getName() : string
    {
        return $this->schemaDefinition[\PoP\API\Schema\SchemaDefinition::ARGNAME_NAME];
    }
    public function getDescription() : ?string
    {
        return $this->schemaDefinition[\PoP\API\Schema\SchemaDefinition::ARGNAME_DESCRIPTION] ?? null;
    }
    public function isDeprecated() : bool
    {
        return $this->schemaDefinition[\PoP\API\Schema\SchemaDefinition::ARGNAME_DEPRECATED] ?? \false;
    }
    public function getDeprecationDescription() : ?string
    {
        return $this->schemaDefinition[\PoP\API\Schema\SchemaDefinition::ARGNAME_DEPRECATIONDESCRIPTION] ?? null;
    }
    public function getExtensions() : array
    {
        $extensions = [];
        if ($version = $this->schemaDefinition[\PoP\API\Schema\SchemaDefinition::ARGNAME_VERSION] ?? null) {
            $extensions[\PoP\API\Schema\SchemaDefinition::ARGNAME_VERSION] = $version;
        }
        if ($this->schemaDefinition[\PoP\API\Schema\SchemaDefinition::ARGNAME_FIELD_IS_MUTATION] ?? null) {
            $extensions[\PoP\API\Schema\SchemaDefinition::ARGNAME_FIELD_IS_MUTATION] = \true;
        }
        return $extensions;
    }
}
